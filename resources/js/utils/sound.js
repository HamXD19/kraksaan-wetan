import { ref } from 'vue';

// Reactive state for sound toggle (default: enabled)
export const isSoundEnabled = ref(
  typeof window !== 'undefined'
    ? localStorage.getItem('portal_sound_enabled') !== 'false'
    : true
);

let audioCtx = null;

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

/**
 * Play a subtle, tactile soft-click sound (ideal for buttons, links, menus)
 */
export const playClick = () => {
  if (!isSoundEnabled.value) return;
  try {
    const ctx = getAudioContext();
    if (!ctx) return;

    const now = ctx.currentTime;
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    // Subtle soft pop: drops frequency quickly from 650Hz to 160Hz
    osc.type = 'sine';
    osc.frequency.setValueAtTime(650, now);
    osc.frequency.exponentialRampToValueAtTime(160, now + 0.035);

    // Smooth volume envelope (peak 10% volume, fast decay)
    gain.gain.setValueAtTime(0.0001, now);
    gain.gain.linearRampToValueAtTime(0.12, now + 0.003);
    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.035);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start(now);
    osc.stop(now + 0.04);
  } catch (e) {
    // Non-critical, ignore if audio context isn't permitted yet
  }
};

/**
 * Play a gentle melodic tone for navigation
 */
export const playNav = () => {
  if (!isSoundEnabled.value) return;
  try {
    const ctx = getAudioContext();
    if (!ctx) return;

    const now = ctx.currentTime;
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = 'sine';
    osc.frequency.setValueAtTime(520, now);
    osc.frequency.exponentialRampToValueAtTime(330, now + 0.06);

    gain.gain.setValueAtTime(0.0001, now);
    gain.gain.linearRampToValueAtTime(0.09, now + 0.005);
    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.06);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start(now);
    osc.stop(now + 0.07);
  } catch (e) {
  }
};

/**
 * Play a double confirmation chirp when turning sound on
 */
export const playToggleOn = () => {
  try {
    const ctx = getAudioContext();
    if (!ctx) return;

    const now = ctx.currentTime;
    // Tone 1
    const osc1 = ctx.createOscillator();
    const gain1 = ctx.createGain();
    osc1.type = 'sine';
    osc1.frequency.setValueAtTime(440, now);
    gain1.gain.setValueAtTime(0.001, now);
    gain1.gain.linearRampToValueAtTime(0.08, now + 0.01);
    gain1.gain.exponentialRampToValueAtTime(0.0001, now + 0.05);
    osc1.connect(gain1);
    gain1.connect(ctx.destination);
    osc1.start(now);
    osc1.stop(now + 0.055);

    // Tone 2 (higher, cheerful)
    const osc2 = ctx.createOscillator();
    const gain2 = ctx.createGain();
    osc2.type = 'sine';
    osc2.frequency.setValueAtTime(660, now + 0.06);
    gain2.gain.setValueAtTime(0.001, now + 0.06);
    gain2.gain.linearRampToValueAtTime(0.1, now + 0.07);
    gain2.gain.exponentialRampToValueAtTime(0.0001, now + 0.12);
    osc2.connect(gain2);
    gain2.connect(ctx.destination);
    osc2.start(now + 0.06);
    osc2.stop(now + 0.13);
  } catch (e) {
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
    playToggleOn();
  }
};

/**
 * Setup global listeners to play audio on click of links/buttons/menu items
 */
export const setupSoundInteractions = () => {
  if (typeof window === 'undefined') return;

  // Intercept user pointerdown gesture globally
  document.addEventListener('pointerdown', (event) => {
    const target = event.target;
    if (!target || typeof target.closest !== 'function') return;

    const interactiveEl = target.closest(
      'a, button, [role="button"], router-link, input[type="button"], input[type="submit"], .cursor-pointer'
    );

    if (interactiveEl) {
      playClick();
    }
  }, { passive: true });
};
