import { ref } from 'vue';

// Reactive state for sound toggle (default: enabled)
export const isSoundEnabled = ref(
  typeof window !== 'undefined'
    ? localStorage.getItem('portal_sound_enabled') !== 'false'
    : true
);

let audioCtx = null;
let currentAudio = null;
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
 * Detect whether current view is in the Admin section
 */
export const isAdminArea = () => {
  if (typeof window === 'undefined') return false;
  const path = window.location.pathname || '';
  const hash = window.location.hash || '';
  return path.startsWith('/admin') || hash.startsWith('#/admin') || path.includes('/admin');
};

/**
 * Instantly stop all playing audio and speech synthesis
 */
export const stopAllSound = () => {
  if (currentAudio) {
    try {
      currentAudio.pause();
      currentAudio.currentTime = 0;
    } catch (e) {}
    currentAudio = null;
  }
  if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
    try {
      window.speechSynthesis.cancel();
    } catch (e) {}
  }
};

/**
 * Subtle tactile soft-click feedback (subtle and non-intrusive)
 */
export const playChime = () => {
  if (!isSoundEnabled.value) return;
  if (isAdminArea()) return;

  try {
    const ctx = getAudioContext();
    if (!ctx) return;

    const now = ctx.currentTime;
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'sine';
    osc.frequency.setValueAtTime(650, now);
    osc.frequency.exponentialRampToValueAtTime(320, now + 0.04);

    gain.gain.setValueAtTime(0.0001, now);
    gain.gain.linearRampToValueAtTime(0.08, now + 0.005);
    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.04);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start(now);
    osc.stop(now + 0.045);
  } catch (e) {}
};

export const playClick = playChime;

/**
 * Clean text for natural Indonesian speech pronunciation
 */
export const cleanSpeechText = (rawText) => {
  if (!rawText) return '';
  let text = String(rawText).trim();

  text = text.replace(/\(\d+\)/g, '').trim();

  text = text.replace(/&/g, ' dan ')
             .replace(/\+/g, ' dan ')
             .replace(/\//g, ' atau ')
             .replace(/[•→✓›»]/g, ' ')
             .replace(/\s+/g, ' ')
             .trim();

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

  if (text.length > 2 && text === text.toUpperCase() && /[A-Z]/.test(text)) {
    text = text.toLowerCase().replace(/(?:^|\s)\S/g, a => a.toUpperCase());
  }

  return text;
};

/**
 * Fallback Web Speech API when network audio is unavailable
 */
const fallbackSpeechSynthesis = (cleanText) => {
  if (typeof window === 'undefined' || !('speechSynthesis' in window)) return;
  try {
    const synth = window.speechSynthesis;
    if (synth.paused) synth.resume();
    if (synth.speaking || synth.pending) synth.cancel();

    setTimeout(() => {
      try {
        if (synth.paused) synth.resume();
        const utterance = new SpeechSynthesisUtterance(cleanText);
        utterance.rate = 1.0;
        utterance.volume = 1.0;
        utterance.lang = 'id-ID';

        const voices = synth.getVoices() || [];
        const voice = voices.find(v => 
          v.lang === 'id-ID' || 
          v.lang === 'id_ID' || 
          (typeof v.lang === 'string' && v.lang.toLowerCase().startsWith('id')) || 
          /indonesia/i.test(v.name)
        ) || voices.find(v => v.default) || voices[0];

        if (voice) {
          utterance.voice = voice;
        }

        activeUtterance = utterance;
        window._currentSpeechUtterance = utterance;

        utterance.onend = () => { activeUtterance = null; };
        utterance.onerror = () => { activeUtterance = null; };

        synth.speak(utterance);
      } catch (err) {}
    }, 25);
  } catch (e) {}
};

/**
 * Text-to-Speech: Voice narration that reads the clicked menu text in Indonesian
 * Uses server-cached natural human Indonesian audio MP3 with zero latency
 */
export const speakText = (text) => {
  if (!isSoundEnabled.value) return;
  if (isAdminArea()) return;
  if (!text || typeof text !== 'string') return;

  const clean = cleanSpeechText(text);
  if (!clean) return;

  // 1. Immediately cancel previous audio and speech synthesis
  if (currentAudio) {
    try {
      currentAudio.pause();
      currentAudio.currentTime = 0;
    } catch (e) {}
    currentAudio = null;
  }
  if (typeof window !== 'undefined' && 'speechSynthesis' in window) {
    try {
      window.speechSynthesis.cancel();
    } catch (e) {}
  }

  // 2. Play natural Indonesian voice from server-cached MP3 endpoint
  try {
    const audioUrl = `/api/tts?text=${encodeURIComponent(clean)}`;
    const audio = new Audio(audioUrl);
    audio.volume = 1.0;
    currentAudio = audio;

    const playPromise = audio.play();
    if (playPromise !== undefined) {
      playPromise.catch(() => {
        fallbackSpeechSynthesis(clean);
      });
    }

    audio.onended = () => {
      if (currentAudio === audio) {
        currentAudio = null;
      }
    };

    audio.onerror = () => {
      fallbackSpeechSynthesis(clean);
    };
  } catch (e) {
    fallbackSpeechSynthesis(clean);
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
    playChime();
    speakText('Suara navigasi aktif');
  } else {
    if (currentAudio) {
      try {
        currentAudio.pause();
        currentAudio.currentTime = 0;
      } catch (e) {}
      currentAudio = null;
    }
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

    // 1. Completely ignore all clicks and stop sound in the admin area
    if (isAdminArea() || target.closest('[data-no-sound], .admin-layout, .admin-panel, aside')) {
      stopAllSound();
      return;
    }

    // Ignore clicks on video players, audio, or iframes
    if (target.closest('iframe, video, audio, .video-player')) return;

    // Ignore sound toggle button itself (toggleSound handles its own feedback)
    if (target.closest('[data-sound-toggle]')) return;

    // Find closest interactive element
    const interactiveEl = target.closest(
      'a, button, [role="button"], router-link, input[type="button"], input[type="submit"], [data-speech], .cursor-pointer'
    );

    if (interactiveEl) {
      // Ignore if element is marked data-no-sound or navigates to admin
      if (interactiveEl.closest('[data-no-sound]')) {
        stopAllSound();
        return;
      }
      const targetHref = interactiveEl.getAttribute('href') || interactiveEl.getAttribute('to') || '';
      if (targetHref.startsWith('/admin') || targetHref.includes('/admin')) {
        stopAllSound();
        return;
      }

      // Resume AudioContext on valid user gesture
      getAudioContext();

      // 1. Play subtle click pop
      playChime();

      // 2. Read menu text aloud with natural Indonesian voice
      const speechText = getSpeechText(interactiveEl);
      if (speechText) {
        speakText(speechText);
      }
    }
  };

  // 'click' in capture phase ensures standard user gesture activation in all modern browsers
  document.addEventListener('click', handleInteraction, true);

  // Pre-warm audio engine on first touch or pointer down
  const unlockAudio = () => {
    getAudioContext();
    window.removeEventListener('pointerdown', unlockAudio);
    window.removeEventListener('touchstart', unlockAudio);
  };
  window.addEventListener('pointerdown', unlockAudio, { passive: true });
  window.addEventListener('touchstart', unlockAudio, { passive: true });
};
