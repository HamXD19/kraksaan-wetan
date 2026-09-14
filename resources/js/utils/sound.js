import { ref } from 'vue';

// Reactive state for sound toggle (default: enabled)
export const isSoundEnabled = ref(
  typeof window !== 'undefined'
    ? localStorage.getItem('portal_sound_enabled') !== 'false'
    : true
);

let audioCtx = null;
let cachedVoice = null;
// CRITICAL: Chromium V8 Garbage Collection bug workaround
// Keeps a global reference so Chrome doesn't garbage collect the utterance before it speaks
let activeUtterance = null;

/**
 * Initialize or resume Web Audio Context on valid user gesture
 */
export const getAudioContext = () => {
  if (typeof window === 'undefined') return null;
  try {
    if (!audioCtx) {
      const AudioContextClass = window.AudioContext || window.webkitAudioContext;
      if (AudioContextClass) {
        audioCtx = new AudioContextClass();
      }
    }
    if (audioCtx && audioCtx.state === 'suspended') {
      audioCtx.resume().catch(() => {});
    }
  } catch (e) {}
  return audioCtx;
};

/**
 * Play a clear, crisp, modern UI chime tone
 * Guarantees immediate audible feedback on all devices & browsers
 */
export const playChime = () => {
  if (!isSoundEnabled.value) return;
  try {
    const ctx = getAudioContext();
    if (!ctx) return;

    const now = ctx.currentTime;
    // Pleasant 2-tone melodic soft chime (E5 -> A5)
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'sine';
    osc.frequency.setValueAtTime(659.25, now); // E5
    osc.frequency.exponentialRampToValueAtTime(880.0, now + 0.08); // A5

    gain.gain.setValueAtTime(0.001, now);
    gain.gain.linearRampToValueAtTime(0.18, now + 0.015);
    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.16);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start(now);
    osc.stop(now + 0.17);
  } catch (e) {}
};

// Aliases for compatibility
export const playClick = playChime;

/**
 * Load available voices reliably from browser
 */
const getIndonesianVoice = () => {
  if (typeof window === 'undefined' || !('speechSynthesis' in window)) return null;
  if (cachedVoice) return cachedVoice;

  try {
    const voices = window.speechSynthesis.getVoices() || [];
    if (!voices.length) return null;

    // 1. Search for Indonesian voice (id-ID, id_ID, or containing "indonesia")
    cachedVoice = voices.find(v => 
      v.lang === 'id-ID' || 
      v.lang === 'id_ID' || 
      (typeof v.lang === 'string' && v.lang.toLowerCase().startsWith('id')) || 
      /indonesia/i.test(v.name)
    ) || null;

    // 2. If no Indonesian voice installed on OS, fallback to default or first voice
    if (!cachedVoice) {
      cachedVoice = voices.find(v => v.default) || voices[0] || null;
    }
  } catch (e) {}

  return cachedVoice;
};

// Listen for browser voice population
if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
  window.speechSynthesis.onvoiceschanged = () => {
    cachedVoice = null;
    getIndonesianVoice();
  };
  getIndonesianVoice();
}

/**
 * Clean text for natural Indonesian speech pronunciation
 */
export const cleanSpeechText = (rawText) => {
  if (!rawText) return '';
  let text = String(rawText).trim();

  // Remove count badges like (3), numbers at end
  text = text.replace(/\(\d+\)/g, '').trim();

  // Replace common symbols with Indonesian words
  text = text.replace(/&/g, ' dan ')
             .replace(/\+/g, ' dan ')
             .replace(/\//g, ' atau ')
             .replace(/[•→✓›»]/g, ' ')
             .replace(/\s+/g, ' ')
             .trim();

  // Common Kelurahan acronyms expanded for natural reading
  text = text.replace(/\bRT\b/gi, 'R T')
             .replace(/\bRW\b/gi, 'R W')
             .replace(/\bLKK\b/gi, 'Lembaga Kemasyarakatan')
             .replace(/\bHUT\b/gi, 'Hari Ulang Tahun')
             .replace(/\bUMKM\b/gi, 'U M K M')
             .replace(/\bYT\b/gi, 'Video')
             .replace(/\bKTP\b/gi, 'K T P')
             .replace(/\bKK\b/gi, 'K K')
             .replace(/\bNIK\b/gi, 'N I K')
             .replace(/\bSPPT\b/gi, 'S P P T')
             .replace(/\bPBB\b/gi, 'P B B');

  // If text is ALL CAPS, convert to Title Case for natural pronunciation
  if (text.length > 2 && text === text.toUpperCase() && /[A-Z]/.test(text)) {
    text = text.toLowerCase().replace(/(?:^|\s)\S/g, a => a.toUpperCase());
  }

  return text;
};

/**
 * Text-to-Speech: Voice narration that reads the clicked menu text
 */
export const speakText = (text) => {
  if (!isSoundEnabled.value) return;
  if (typeof window === 'undefined' || !('speechSynthesis' in window)) return;
  if (!text || typeof text !== 'string') return;

  const clean = cleanSpeechText(text);
  if (!clean) return;

  try {
    const synth = window.speechSynthesis;

    // Wake up synth if paused (common Chrome bug)
    if (synth.paused) {
      synth.resume();
    }

    // Cancel ongoing speech so new click speaks immediately
    if (synth.speaking || synth.pending) {
      synth.cancel();
    }

    // Chrome bug fix: cancel() is async. Calling speak() in the exact same event loop frame
    // causes the new utterance to be cancelled by the pending cancel command!
    // A 35ms setTimeout prevents this race condition completely.
    setTimeout(() => {
      try {
        if (synth.paused) {
          synth.resume();
        }

        const utterance = new SpeechSynthesisUtterance(clean);
        utterance.rate = 1.0;
        utterance.pitch = 1.0;
        utterance.volume = 1.0;

        const voice = getIndonesianVoice();
        if (voice) {
          utterance.voice = voice;
          utterance.lang = voice.lang || 'id-ID';
        } else {
          utterance.lang = 'id-ID';
        }

        // Keep persistent reference to avoid Chromium V8 GC bug
        activeUtterance = utterance;
        window._currentSpeechUtterance = utterance;

        utterance.onend = () => {
          activeUtterance = null;
          window._currentSpeechUtterance = null;
        };

        utterance.onerror = () => {
          activeUtterance = null;
          window._currentSpeechUtterance = null;
        };

        synth.speak(utterance);
      } catch (err) {}
    }, 35);
  } catch (e) {}
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
    playChime();
    speakText('Suara navigasi aktif');
  } else {
    if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
      window.speechSynthesis.cancel();
    }
  }
};

/**
 * Get readable speech text from element or its ancestors
 */
export const getSpeechText = (el) => {
  if (!el) return null;

  // 1. Check explicit data-speech attribute on element or ancestors
  const speechEl = el.hasAttribute('data-speech') ? el : el.closest('[data-speech]');
  if (speechEl) {
    const val = speechEl.getAttribute('data-speech');
    if (val) return cleanSpeechText(val);
  }

  // 2. Check title or aria-label
  const aria = el.getAttribute('aria-label') || el.getAttribute('title');
  if (aria && aria.length < 50 && !/buka|tutup|menu/i.test(aria)) {
    return cleanSpeechText(aria);
  }

  // 3. Check text content for navigation links/buttons
  const isNav = el.closest('header, nav, [role="navigation"], aside, .menu, ul, ol');
  if (isNav) {
    const text = el.innerText || el.textContent;
    if (text) {
      const cleaned = cleanSpeechText(text);
      if (cleaned.length >= 2 && cleaned.length <= 60) {
        return cleaned;
      }
    }
  }

  return null;
};

/**
 * Setup global listeners to play audio on user click
 */
export const setupSoundInteractions = () => {
  if (typeof window === 'undefined') return;

  // Handle user clicks with capture phase to guarantee interception before stopPropagation
  const handleInteraction = (event) => {
    const target = event.target;
    if (!target || typeof target.closest !== 'function') return;

    // Ignore clicks on video players, audio, or iframes
    if (target.closest('iframe, video, audio, .video-player')) return;

    // Ignore sound toggle button itself (toggleSound handles its own feedback)
    if (target.closest('[data-sound-toggle]')) return;

    // Find closest interactive element
    const interactiveEl = target.closest(
      'a, button, [role="button"], router-link, input[type="button"], input[type="submit"], [data-speech], .cursor-pointer'
    );

    if (interactiveEl) {
      // Resume AudioContext on valid user gesture
      getAudioContext();

      // 1. Play audible modern chime
      playChime();

      // 2. Read menu text aloud via SpeechSynthesis
      const speechText = getSpeechText(interactiveEl);
      if (speechText) {
        speakText(speechText);
      }
    }
  };

  // 'click' in capture phase ensures standard user gesture activation in all modern browsers
  document.addEventListener('click', handleInteraction, true);

  // Pre-warm audio and voice engine on first pointer down / touch
  const unlockAudio = () => {
    getAudioContext();
    getIndonesianVoice();
    window.removeEventListener('pointerdown', unlockAudio);
    window.removeEventListener('touchstart', unlockAudio);
  };
  window.addEventListener('pointerdown', unlockAudio, { passive: true });
  window.addEventListener('touchstart', unlockAudio, { passive: true });
};
