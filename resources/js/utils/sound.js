import { ref } from 'vue';

// Reactive state for sound toggle (default: enabled)
export const isSoundEnabled = ref(
  typeof window !== 'undefined'
    ? localStorage.getItem('portal_sound_enabled') !== 'false'
    : true
);

let audioCtx = null;
let cachedVoice = null;

// Initialize Web Audio Context on user gesture
const getAudioContext = () => {
  if (typeof window === 'undefined') return null;
  if (!audioCtx) {
    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    if (AudioContextClass) {
      audioCtx = new AudioContextClass();
    }
  }
  if (audioCtx && audioCtx.state === 'suspended') {
    audioCtx.resume().catch(() => {});
  }
  return audioCtx;
};

// Find Indonesian voice in browser
const getIndonesianVoice = () => {
  if (typeof window === 'undefined' || !('speechSynthesis' in window)) return null;
  if (cachedVoice) return cachedVoice;

  try {
    const voices = window.speechSynthesis.getVoices() || [];
    // Prioritize ID locale voice (e.g., id-ID, id_ID, or containing Indonesian)
    cachedVoice = voices.find(v => 
      v.lang === 'id-ID' || 
      v.lang.startsWith('id') || 
      /indonesia/i.test(v.name)
    ) || null;
  } catch (e) {}

  return cachedVoice;
};

if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
  window.speechSynthesis.onvoiceschanged = () => {
    cachedVoice = null;
    getIndonesianVoice();
  };
}

/**
 * Play tactile subtle click feedback
 */
export const playClick = () => {
  if (!isSoundEnabled.value) return;
  try {
    const ctx = getAudioContext();
    if (!ctx) return;

    const now = ctx.currentTime;
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'sine';
    osc.frequency.setValueAtTime(650, now);
    osc.frequency.exponentialRampToValueAtTime(180, now + 0.035);

    gain.gain.setValueAtTime(0.0001, now);
    gain.gain.linearRampToValueAtTime(0.12, now + 0.003);
    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.035);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start(now);
    osc.stop(now + 0.04);
  } catch (e) {}
};

/**
 * Text-to-Speech: Voice narration that reads the clicked menu text in Indonesian
 */
export const speakText = (text) => {
  if (!isSoundEnabled.value) return;
  if (typeof window === 'undefined' || !('speechSynthesis' in window)) return;
  if (!text || typeof text !== 'string') return;

  const clean = text.trim();
  if (!clean) return;

  try {
    // Cancel previous speech so the new menu text is spoken immediately
    window.speechSynthesis.cancel();

    const utterance = new SpeechSynthesisUtterance(clean);
    utterance.lang = 'id-ID';
    utterance.rate = 1.05; // natural speaking rate
    utterance.pitch = 1.0;

    const voice = getIndonesianVoice();
    if (voice) {
      utterance.voice = voice;
    }

    window.speechSynthesis.speak(utterance);
  } catch (e) {
    // Speech synthesis error fallback
  }
};

/**
 * Toggle sound on or off
 */
export const toggleSound = () => {
  isSoundEnabled.value = !isSoundEnabled.value;
  if (typeof window !== 'undefined') {
    localStorage.setItem('portal_sound_enabled', isSoundEnabled.value ? 'true' : 'false');
  }

  if (isSoundEnabled.value) {
    speakText('Suara menu aktif');
  } else {
    if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
      window.speechSynthesis.cancel();
    }
  }
};

/**
 * Clean and format text extracted from clicked elements
 */
const cleanSpeechText = (rawText) => {
  if (!rawText) return '';
  let text = String(rawText).trim();

  // Remove count badges like (3), numbers at end
  text = text.replace(/\(\d+\)/g, '').trim();

  // Replace common symbols with words
  text = text.replace(/&/g, ' dan ')
             .replace(/\//g, ' atau ')
             .replace(/•/g, '')
             .replace(/→/g, '')
             .replace(/✓/g, '')
             .replace(/\s+/g, ' ')
             .trim();

  // Common Kelurahan acronyms
  text = text.replace(/\bRT\b/gi, 'R T')
             .replace(/\bRW\b/gi, 'R W')
             .replace(/\bLKK\b/gi, 'Lembaga Kemasyarakatan')
             .replace(/\bHUT\b/gi, 'Hari Ulang Tahun')
             .replace(/\bUMKM\b/gi, 'U M K M')
             .replace(/\bYT\b/gi, 'Video');

  // If text is ALL CAPS, convert to Title Case for natural pronunciation
  if (text.length > 2 && text === text.toUpperCase() && /[A-Z]/.test(text)) {
    text = text.toLowerCase().replace(/(?:^|\s)\S/g, a => a.toUpperCase());
  }

  return text;
};

/**
 * Get readable speech text from element or its ancestors
 */
const getSpeechText = (el) => {
  if (!el) return null;

  // 1. Check explicit data-speech attribute
  const explicit = el.getAttribute('data-speech') || el.closest('[data-speech]')?.getAttribute('data-speech');
  if (explicit) return explicit;

  // 2. Check title or aria-label
  const aria = el.getAttribute('aria-label') || el.getAttribute('title');
  if (aria && aria.length < 50 && !/buka|tutup|menu/i.test(aria)) {
    return cleanSpeechText(aria);
  }

  // 3. Check text content for navigation links/buttons
  const isNav = el.closest('header, nav, [role="navigation"], aside, .menu');
  if (isNav) {
    const text = el.innerText || el.textContent;
    if (text) {
      const cleaned = cleanSpeechText(text);
      if (cleaned.length >= 2 && cleaned.length <= 50) {
        return cleaned;
      }
    }
  }

  return null;
};

/**
 * Setup global listeners to play click audio and speak menu name
 */
export const setupSoundInteractions = () => {
  if (typeof window === 'undefined') return;

  document.addEventListener('pointerdown', (event) => {
    const target = event.target;
    if (!target || typeof target.closest !== 'function') return;

    // Ignore clicks on video players or inside iframes
    if (target.closest('iframe, video, audio')) return;

    const interactiveEl = target.closest(
      'a, button, [role="button"], router-link, input[type="button"], input[type="submit"], .cursor-pointer'
    );

    if (interactiveEl) {
      // 1. Play tactile click sound
      playClick();

      // 2. Read menu aloud if speech text is available
      const speechText = getSpeechText(interactiveEl);
      if (speechText) {
        speakText(speechText);
      }
    }
  }, { passive: true });
};
