import { reactive, readonly } from 'vue';

const state = reactive({
  toasts: [],
});

let nextId = 1;

export function useToast() {
  const show = ({ type = 'success', title = '', message = '', duration = 4500 }) => {
    const id = nextId++;
    const defaultTitles = {
      success: 'Berhasil Disimpan!',
      error: 'Terjadi Kendala!',
      warning: 'Perhatian',
      info: 'Informasi',
    };

    const toastItem = {
      id,
      type,
      title: title || defaultTitles[type] || 'Notifikasi',
      message: message || '',
      duration,
      progress: 100,
      createdAt: Date.now(),
    };

    // Keep at most 4 toasts visible at once
    if (state.toasts.length >= 4) {
      const oldest = state.toasts[0];
      if (oldest && oldest._timer) clearInterval(oldest._timer);
      state.toasts.shift();
    }

    state.toasts.push(toastItem);

    if (duration > 0) {
      const interval = 40;
      const step = (interval / duration) * 100;
      const timer = setInterval(() => {
        toastItem.progress -= step;
        if (toastItem.progress <= 0) {
          clearInterval(timer);
          remove(id);
        }
      }, interval);
      toastItem._timer = timer;
    }

    return id;
  };

  const success = (message, title = 'Berhasil Disimpan!') => {
    return show({ type: 'success', title, message });
  };

  const error = (message, title = 'Gagal Menyimpan!') => {
    return show({ 
      type: 'error', 
      title, 
      message: message || 'Terjadi kesalahan saat memproses data. Silakan periksa kembali isian formulir Anda.' 
    });
  };

  const warning = (message, title = 'Perhatian') => {
    return show({ type: 'warning', title, message });
  };

  const info = (message, title = 'Informasi') => {
    return show({ type: 'info', title, message });
  };

  const remove = (id) => {
    const idx = state.toasts.findIndex(t => t.id === id);
    if (idx !== -1) {
      if (state.toasts[idx]._timer) {
        clearInterval(state.toasts[idx]._timer);
      }
      state.toasts.splice(idx, 1);
    }
  };

  const clear = () => {
    state.toasts.forEach(t => {
      if (t._timer) clearInterval(t._timer);
    });
    state.toasts = [];
  };

  return {
    toasts: readonly(state.toasts),
    show,
    success,
    error,
    warning,
    info,
    remove,
    clear,
  };
}

// Global window helper and polite window.alert replacement
if (typeof window !== 'undefined') {
  const globalToast = useToast();
  window.$toast = globalToast;

  // Intercept window.alert so any validation alert turns into a gorgeous popup
  window.alert = (msg) => {
    const text = String(msg || '');
    const lower = text.toLowerCase();
    if (lower.includes('gagal') || lower.includes('tidak valid') || lower.includes('terlalu besar') || lower.includes('error') || lower.includes('harap ')) {
      globalToast.error(text, 'Validasi Gagal');
    } else if (lower.includes('berhasil') || lower.includes('sukses')) {
      globalToast.success(text, 'Berhasil');
    } else {
      globalToast.info(text, 'Pemberitahuan');
    }
  };
}
