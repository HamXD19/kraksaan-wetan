<template>
  <div class="rich-text-editor rounded-xl border border-slate-200 bg-white overflow-hidden shadow-xs focus-within:ring-2 focus-within:ring-emerald-600/20 focus-within:border-emerald-600 transition-all">
    <!-- Word-like Toolbar Ribbon -->
    <div class="bg-slate-50 border-b border-slate-200 p-2 flex flex-wrap items-center gap-1 text-slate-700 select-none">
      <!-- Heading / Paragraph Selector -->
      <div class="relative inline-block mr-1">
        <select 
          :disabled="isHtmlMode"
          @change="formatBlock($event.target.value); $event.target.value = ''"
          class="h-8 pl-2 pr-7 text-xs font-semibold bg-white border border-slate-200 rounded-lg hover:bg-slate-100/80 focus:ring-1 focus:ring-emerald-600 outline-none cursor-pointer disabled:opacity-50 appearance-none"
          title="Format Teks / Paragraf"
        >
          <option value="" disabled selected>Format Teks</option>
          <option value="p">Normal (Paragraf)</option>
          <option value="h2">Judul Utama (H2)</option>
          <option value="h3">Sub Judul (H3)</option>
          <option value="h4">Judul Kecil (H4)</option>
          <option value="blockquote">Kutipan (Blockquote)</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1.5 text-slate-400">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
      </div>

      <div class="h-5 w-px bg-slate-200 mx-0.5"></div>

      <!-- Basic Formatting (Bold, Italic, Underline, Strikethrough) -->
      <div class="flex items-center gap-0.5">
        <button
          type="button"
          @click="exec('bold')"
          :disabled="isHtmlMode"
          :class="[isActive('bold') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs font-bold transition disabled:opacity-40"
          title="Tebal (Ctrl+B)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 12h8a4 4 0 000-8H6v8zm0 0h9a4 4 0 010 8H6v-8z"/></svg>
        </button>

        <button
          type="button"
          @click="exec('italic')"
          :disabled="isHtmlMode"
          :class="[isActive('italic') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs italic transition disabled:opacity-40"
          title="Miring (Ctrl+I)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 0h-6m2 16H8"/></svg>
        </button>

        <button
          type="button"
          @click="exec('underline')"
          :disabled="isHtmlMode"
          :class="[isActive('underline') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs underline transition disabled:opacity-40"
          title="Garis Bawah (Ctrl+U)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3v7a6 6 0 0012 0V3M4 21h16"/></svg>
        </button>

        <button
          type="button"
          @click="exec('strikeThrough')"
          :disabled="isHtmlMode"
          :class="[isActive('strikeThrough') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs transition disabled:opacity-40"
          title="Coret Teks (Strikethrough)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M16 6a4 4 0 00-8 0c0 2 2 3 4 3s4 1 4 3a4 4 0 01-8 0"/></svg>
        </button>
      </div>

      <div class="h-5 w-px bg-slate-200 mx-0.5"></div>

      <!-- Alignment (Left, Center, Right, Justify) -->
      <div class="flex items-center gap-0.5">
        <button
          type="button"
          @click="exec('justifyLeft')"
          :disabled="isHtmlMode"
          :class="[isActive('justifyLeft') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs transition disabled:opacity-40"
          title="Rata Kiri"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14"/></svg>
        </button>

        <button
          type="button"
          @click="exec('justifyCenter')"
          :disabled="isHtmlMode"
          :class="[isActive('justifyCenter') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs transition disabled:opacity-40"
          title="Rata Tengah"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14"/></svg>
        </button>

        <button
          type="button"
          @click="exec('justifyRight')"
          :disabled="isHtmlMode"
          :class="[isActive('justifyRight') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs transition disabled:opacity-40"
          title="Rata Kanan"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14"/></svg>
        </button>

        <button
          type="button"
          @click="exec('justifyFull')"
          :disabled="isHtmlMode"
          :class="[isActive('justifyFull') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs transition disabled:opacity-40"
          title="Rata Kanan Kiri (Justify)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>

      <div class="h-5 w-px bg-slate-200 mx-0.5"></div>

      <!-- Lists -->
      <div class="flex items-center gap-0.5">
        <button
          type="button"
          @click="exec('insertUnorderedList')"
          :disabled="isHtmlMode"
          :class="[isActive('insertUnorderedList') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs transition disabled:opacity-40"
          title="Daftar Poin (Bullet List)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h.01M4 12h.01M4 18h.01M8 6h12M8 12h12M8 18h12"/></svg>
        </button>

        <button
          type="button"
          @click="exec('insertOrderedList')"
          :disabled="isHtmlMode"
          :class="[isActive('insertOrderedList') ? 'bg-emerald-100 text-emerald-800' : 'hover:bg-white text-slate-700']"
          class="p-1.5 rounded-lg text-xs transition disabled:opacity-40"
          title="Daftar Bernomor (Numbered List)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 5h1v3H3m0 3h2v1H4v1h1v1H3m0 3h2v3H3"/></svg>
        </button>
      </div>

      <div class="h-5 w-px bg-slate-200 mx-0.5"></div>

      <!-- Links, HR, Clear format -->
      <div class="flex items-center gap-0.5">
        <button
          type="button"
          @click="insertLink"
          :disabled="isHtmlMode"
          class="p-1.5 rounded-lg hover:bg-white text-slate-700 text-xs transition disabled:opacity-40"
          title="Sisipkan Tautan (Link)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
        </button>

        <button
          type="button"
          @click="exec('insertHorizontalRule')"
          :disabled="isHtmlMode"
          class="p-1.5 rounded-lg hover:bg-white text-slate-700 text-xs transition disabled:opacity-40"
          title="Garis Pembatas (Horizontal Line)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/></svg>
        </button>

        <button
          type="button"
          @click="exec('removeFormat')"
          :disabled="isHtmlMode"
          class="p-1.5 rounded-lg hover:bg-white text-rose-600 text-xs transition disabled:opacity-40"
          title="Hapus Pemformatan (Clear Format)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <div class="h-5 w-px bg-slate-200 mx-0.5"></div>

      <!-- Undo / Redo -->
      <div class="flex items-center gap-0.5">
        <button
          type="button"
          @click="exec('undo')"
          :disabled="isHtmlMode"
          class="p-1.5 rounded-lg hover:bg-white text-slate-700 text-xs transition disabled:opacity-40"
          title="Urungkan (Undo)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2M3 10l6 6m-6-6l6-6"/></svg>
        </button>

        <button
          type="button"
          @click="exec('redo')"
          :disabled="isHtmlMode"
          class="p-1.5 rounded-lg hover:bg-white text-slate-700 text-xs transition disabled:opacity-40"
          title="Ulangi (Redo)"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m15-7l-6 6m6-6l-6-6"/></svg>
        </button>
      </div>

      <!-- Right utility (HTML Toggle) -->
      <div class="ml-auto flex items-center gap-1">
        <button
          type="button"
          @click="toggleHtmlMode"
          :class="[isHtmlMode ? 'bg-emerald-700 text-white font-bold' : 'hover:bg-white text-slate-600']"
          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs transition border border-slate-200 shadow-2xs"
          :title="isHtmlMode ? 'Kembali ke Tampilan Visual' : 'Beralih ke Mode Kode HTML'"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
          <span>{{ isHtmlMode ? 'Visual' : 'HTML' }}</span>
        </button>
      </div>
    </div>

    <!-- Visual Editor Area -->
    <div 
      v-show="!isHtmlMode"
      ref="editorRef"
      contenteditable="true"
      @input="onInput"
      @paste="onPaste"
      @focus="isFocused = true"
      @blur="isFocused = false"
      :style="{ minHeight: height, maxHeight: maxHeight }"
      class="editor-content p-3 outline-none text-slate-800 text-xs sm:text-sm leading-relaxed overflow-y-auto"
      :data-placeholder="placeholder"
    ></div>

    <!-- HTML Source Code Mode Area -->
    <textarea
      v-show="isHtmlMode"
      v-model="rawHtml"
      @input="onRawHtmlInput"
      :style="{ minHeight: height, maxHeight: maxHeight }"
      class="w-full p-3 font-mono text-xs text-slate-800 bg-slate-900/5 outline-none resize-y"
      placeholder="Ketik atau edit kode HTML..."
    ></textarea>

    <!-- Bottom Status Bar -->
    <div class="bg-slate-50 border-t border-slate-100 px-3 py-1.5 flex items-center justify-between text-[11px] text-slate-400">
      <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1 font-medium text-slate-500">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
          {{ isHtmlMode ? 'Mode Kode HTML' : 'Mode Visual (Microsoft Word Style)' }}
        </span>
      </div>
      <div class="flex items-center gap-3">
        <span>{{ wordCount }} kata</span>
        <span>&bull;</span>
        <span>{{ charCount }} karakter</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: 'Tulis paragraf atau deskripsi di sini...'
  },
  height: {
    type: String,
    default: '160px'
  },
  maxHeight: {
    type: String,
    default: '260px'
  }
});

const emit = defineEmits(['update:modelValue']);

const editorRef = ref(null);
const isHtmlMode = ref(false);
const rawHtml = ref('');
const isFocused = ref(false);

// Format block (Heading / Paragraph / Blockquote)
const formatBlock = (tag) => {
  if (!tag) return;
  if (tag === 'p') {
    document.execCommand('formatBlock', false, '<p>');
  } else if (tag === 'blockquote') {
    document.execCommand('formatBlock', false, '<blockquote>');
  } else {
    document.execCommand('formatBlock', false, `<${tag}>`);
  }
  syncContent();
};

const exec = (command, value = null) => {
  document.execCommand(command, false, value);
  syncContent();
};

const isActive = (command) => {
  try {
    return document.queryCommandState(command);
  } catch (e) {
    return false;
  }
};

const insertLink = () => {
  const url = prompt('Masukkan URL tautan web (misal: https://...):');
  if (url) {
    exec('createLink', url);
  }
};

const syncContent = () => {
  if (!editorRef.value) return;
  let html = editorRef.value.innerHTML;
  if (html === '<p><br></p>' || html === '<br>') {
    html = '';
  }
  rawHtml.value = html;
  emit('update:modelValue', html);
};

const onInput = () => {
  syncContent();
};

const onRawHtmlInput = () => {
  if (editorRef.value) {
    editorRef.value.innerHTML = rawHtml.value;
  }
  emit('update:modelValue', rawHtml.value);
};

const toggleHtmlMode = () => {
  if (isHtmlMode.value) {
    // Switch from HTML to visual
    if (editorRef.value) {
      editorRef.value.innerHTML = rawHtml.value;
    }
    isHtmlMode.value = false;
  } else {
    // Switch from visual to HTML
    if (editorRef.value) {
      rawHtml.value = editorRef.value.innerHTML;
    }
    isHtmlMode.value = true;
  }
};

// Paste cleaner for Microsoft Word styles
const onPaste = (e) => {
  e.preventDefault();
  const text = (e.originalEvent || e).clipboardData.getData('text/plain');
  document.execCommand('insertText', false, text);
  syncContent();
};

// Word and character count
const plainText = computed(() => {
  const div = document.createElement('div');
  div.innerHTML = props.modelValue || '';
  return div.textContent || div.innerText || '';
});

const wordCount = computed(() => {
  const str = plainText.value.trim();
  if (!str) return 0;
  return str.split(/\s+/).filter(Boolean).length;
});

const charCount = computed(() => {
  return plainText.value.length;
});

// Sync from external modelValue updates (e.g. edit item loaded, reset form)
watch(
  () => props.modelValue,
  (newVal) => {
    const val = newVal || '';
    if (editorRef.value && editorRef.value.innerHTML !== val) {
      // Don't overwrite if user is actively typing in the contenteditable
      if (document.activeElement !== editorRef.value) {
        editorRef.value.innerHTML = val;
      }
    }
    rawHtml.value = val;
  }
);

onMounted(() => {
  if (editorRef.value) {
    editorRef.value.innerHTML = props.modelValue || '';
  }
  rawHtml.value = props.modelValue || '';
});
</script>

<style scoped>
.editor-content:empty:before {
  content: attr(data-placeholder);
  color: #94a3b8;
  pointer-events: none;
  display: block;
}

/* Rich text standard typography within editor */
:deep(h2) {
  font-size: 1.4rem;
  font-weight: 700;
  margin-top: 0.8rem;
  margin-bottom: 0.4rem;
  color: #0f172a;
}
:deep(h3) {
  font-size: 1.2rem;
  font-weight: 700;
  margin-top: 0.6rem;
  margin-bottom: 0.3rem;
  color: #1e293b;
}
:deep(h4) {
  font-size: 1.05rem;
  font-weight: 600;
  margin-top: 0.5rem;
  margin-bottom: 0.25rem;
  color: #334155;
}
:deep(p) {
  margin-bottom: 0.6rem;
  line-height: 1.65;
}
:deep(ul) {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 0.6rem;
}
:deep(ol) {
  list-style-type: decimal;
  padding-left: 1.5rem;
  margin-bottom: 0.6rem;
}
:deep(li) {
  margin-bottom: 0.2rem;
}
:deep(blockquote) {
  border-left: 4px solid #10b981;
  padding-left: 1rem;
  font-style: italic;
  color: #475569;
  margin: 0.8rem 0;
}
:deep(a) {
  color: #047857;
  text-decoration: underline;
}
:deep(hr) {
  border: 0;
  border-top: 1px solid #e2e8f0;
  margin: 1rem 0;
}
</style>
