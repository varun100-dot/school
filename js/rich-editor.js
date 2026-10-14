/**
 * Zuvio Global School - WordPress-Style Rich Text Editor Component
 * Lightweight, zero-dependency, brand-styled WYSIWYG editor with HTML source mode.
 */
(function() {
  'use strict';

  // SVG Icons for toolbar buttons
  const ICONS = {
    bold: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path></svg>',
    italic: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="19" y1="4" x2="10" y2="4"></line><line x1="14" y1="20" x2="5" y2="20"></line><line x1="15" y1="4" x2="9" y2="20"></line></svg>',
    underline: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"></path><line x1="4" y1="21" x2="20" y2="21"></line></svg>',
    strike: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17.3 4.9c-1.3-.6-2.9-1-4.7-1C8.2 3.9 6 6 6 8.7c0 2.2 1.4 3.7 3.9 4.3"></path><path d="M6.7 19.1c1.3.6 2.9 1 4.7 1 4.4 0 6.6-2.1 6.6-4.8 0-2.2-1.4-3.7-3.9-4.3"></path><line x1="3" y1="12" x2="21" y2="12"></line></svg>',
    textColor: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 20h16"></path><path d="m6 16 6-12 6 12"></path><path d="M8 12h8"></path></svg>',
    highlightColor: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m9 11-6 6v3h3l6-6"></path><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"></path></svg>',
    alignLeft: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>',
    alignCenter: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>',
    alignRight: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="21" y1="10" x2="7" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="7" y2="18"></line></svg>',
    alignJustify: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="10" x2="3" y2="10"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="3" y2="18"></line></svg>',
    listUl: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>',
    listOl: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="10" y1="6" x2="21" y2="6"></line><line x1="10" y1="12" x2="21" y2="12"></line><line x1="10" y1="18" x2="21" y2="18"></line><path d="M4 6h1v4"></path><path d="M4 10h2"></path><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path></svg>',
    outdent: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="7 8 3 12 7 16"></polyline><line x1="21" y1="12" x2="11" y2="12"></line><line x1="21" y1="6" x2="11" y2="6"></line><line x1="21" y1="18" x2="11" y2="18"></line></svg>',
    indent: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="13 8 17 12 13 16"></polyline><line x1="21" y1="12" x2="3" y2="12"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="18" x2="3" y2="18"></line></svg>',
    link: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>',
    unlink: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m18.84 12.25 1.72-1.71a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="m5.16 11.75-1.72 1.71a5 5 0 0 0 7.07 7.07l1.72-1.71"></path><line x1="2" y1="2" x2="22" y2="22"></line></svg>',
    image: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>',
    blockquote: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.75-2-2-2H4c-1.25 0-2 .75-2 2v6c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 1-1 2v3h1z"></path><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.75-2-2-2h-4c-1.25 0-2 .75-2 2v6c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 1-1 2v3h1z"></path></svg>',
    hr: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="6" y2="6"></line><line x1="18" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="6" y2="18"></line><line x1="18" y1="18" x2="21" y2="18"></line></svg>',
    removeFormat: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="20" y2="11"></line><line x1="14" y1="15" x2="20" y2="15"></line><line x1="5" y1="19" x2="19" y2="5"></line></svg>',
    undo: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>',
    redo: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>',
    source: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>',
    mediaPlus: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline><line x1="12" y1="10" x2="12" y2="16"></line><line x1="9" y1="13" x2="15" y2="13"></line></svg>',
    uploadCloud: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path><polyline points="16 16 12 12 8 16"></polyline></svg>'
  };

  const TEXT_COLORS = [
    { label: 'Zuvio Navy', hex: '#062B63' },
    { label: 'Zuvio Deep Navy', hex: '#031B42' },
    { label: 'Zuvio Teal', hex: '#0A8998' },
    { label: 'Zuvio Gold', hex: '#D9A441' },
    { label: 'Dark Charcoal', hex: '#1E293B' },
    { label: 'Slate Gray', hex: '#64748B' },
    { label: 'Crimson Red', hex: '#DC2626' },
    { label: 'Forest Green', hex: '#16A34A' },
    { label: 'Royal Blue', hex: '#2563EB' },
    { label: 'Deep Purple', hex: '#9333EA' }
  ];

  const HIGHLIGHT_COLORS = [
    { label: 'Soft Yellow', hex: '#FEF9C3' },
    { label: 'Soft Teal', hex: '#CCFBF1' },
    { label: 'Soft Blue', hex: '#E0F2FE' },
    { label: 'Soft Green', hex: '#DCFCE7' },
    { label: 'Soft Purple', hex: '#F3E8FF' },
    { label: 'Soft Rose', hex: '#FFE4E6' },
    { label: 'Warm Gold', hex: '#FDE68A' },
    { label: 'Soft Orange', hex: '#FFEDD5' },
    { label: 'Light Gray', hex: '#F1F5F9' },
    { label: 'None', hex: 'transparent' }
  ];

  class ZuvioRichEditor {
    constructor(textarea, options = {}) {
      this.textarea = typeof textarea === 'string' ? document.querySelector(textarea) : textarea;
      if (!this.textarea) {
        console.error('[ZuvioRichEditor] Textarea target not found');
        return;
      }

      this.options = Object.assign({
        csrfToken: document.querySelector('input[name="csrf_token"]')?.value || '',
        uploadUrl: '/admin/blogs?action=upload_editor_image',
        mediaLibraryUrl: '/admin/blogs?action=get_media_library',
        placeholder: 'Write your article here using WordPress-style formatting...'
      }, options);

      this.mode = 'visual'; // 'visual' | 'source'
      this.savedSelection = null;
      this.init();
    }

    init() {
      // Hide original textarea but keep it in form for submission
      this.textarea.style.display = 'none';

      // Build DOM structure
      this.createEditorDOM();

      // Load initial content
      let initialContent = this.textarea.value.trim();
      if (initialContent) {
        // If pure plain text with newlines and no HTML tags, wrap into paragraphs
        if (!/<[a-z][\s\S]*>/i.test(initialContent)) {
          initialContent = initialContent
            .split(/\n\s*\n/)
            .map(p => `<p>${p.trim().replace(/\n/g, '<br>')}</p>`)
            .join('');
        }
      } else {
        initialContent = '<p><br></p>';
      }

      this.visual.innerHTML = initialContent;
      this.source.value = this.formatHTML(initialContent);

      // Event Listeners
      this.bindEvents();
      this.updateStatus();
    }

    createEditorDOM() {
      this.container = document.createElement('div');
      this.container.className = 'wp-editor-container';

      // 1. Top Bar: Add Media Button + Visual/Text Tabs
      const topbar = document.createElement('div');
      topbar.className = 'wp-editor-topbar';
      topbar.innerHTML = `
        <button type="button" class="wp-editor-media-btn" id="wp-add-media-btn">
          ${ICONS.mediaPlus} Add Media
        </button>
        <div class="wp-editor-tabs">
          <button type="button" class="wp-editor-tab active" data-mode="visual">Visual</button>
          <button type="button" class="wp-editor-tab" data-mode="source">Text</button>
        </div>
      `;

      // 2. Main Toolbar
      const toolbar = document.createElement('div');
      toolbar.className = 'wp-editor-toolbar';
      toolbar.innerHTML = `
        <!-- Row 1: Paragraph format, Fonts, Sizes, Basic styles, Colors -->
        <div class="wp-toolbar-group">
          <select class="wp-editor-select" data-command="formatBlock" title="Paragraph Style">
            <option value="p">Paragraph</option>
            <option value="h1">Heading 1</option>
            <option value="h2">Heading 2</option>
            <option value="h3">Heading 3</option>
            <option value="h4">Heading 4</option>
            <option value="blockquote">Quote</option>
            <option value="pre">Preformatted</option>
          </select>

          <select class="wp-editor-select" data-command="fontName" title="Font Family">
            <option value="">Font Family</option>
            <option value="Inter, sans-serif">Inter (Default)</option>
            <option value="'Playfair Display', Georgia, serif">Playfair Display</option>
            <option value="Georgia, serif">Georgia</option>
            <option value="Arial, sans-serif">Arial</option>
            <option value="Tahoma, sans-serif">Tahoma</option>
            <option value="'Trebuchet MS', sans-serif">Trebuchet MS</option>
            <option value="'Times New Roman', serif">Times New Roman</option>
            <option value="'Courier New', monospace">Courier New</option>
          </select>

          <select class="wp-editor-select" data-command="fontSize" title="Font Size">
            <option value="">Font Size</option>
            <option value="12px">12px (Small)</option>
            <option value="14px">14px (Body)</option>
            <option value="16px">16px (Normal)</option>
            <option value="18px">18px (Medium)</option>
            <option value="20px">20px (Large)</option>
            <option value="24px">24px (XL)</option>
            <option value="28px">28px (2XL)</option>
            <option value="32px">32px (Huge)</option>
          </select>
        </div>

        <div class="wp-toolbar-group">
          <button type="button" class="wp-editor-btn" data-command="bold" title="Bold (Ctrl+B)">${ICONS.bold}</button>
          <button type="button" class="wp-editor-btn" data-command="italic" title="Italic (Ctrl+I)">${ICONS.italic}</button>
          <button type="button" class="wp-editor-btn" data-command="underline" title="Underline (Ctrl+U)">${ICONS.underline}</button>
          <button type="button" class="wp-editor-btn" data-command="strikeThrough" title="Strikethrough">${ICONS.strike}</button>
        </div>

        <!-- Color pickers -->
        <div class="wp-toolbar-group">
          <div class="wp-color-btn-wrap">
            <button type="button" class="wp-editor-btn" id="wp-text-color-btn" title="Text Color">
              ${ICONS.textColor}
              <span class="wp-color-indicator" id="wp-text-color-bar"></span>
            </button>
            <div class="wp-color-popover" id="wp-text-color-popover">
              <div class="wp-color-grid" id="wp-text-color-grid"></div>
              <div class="wp-custom-color-row">
                <span>Custom:</span>
                <input type="color" id="wp-custom-text-color" value="#062B63">
              </div>
            </div>
          </div>

          <div class="wp-color-btn-wrap">
            <button type="button" class="wp-editor-btn" id="wp-bg-color-btn" title="Highlight Color">
              ${ICONS.highlightColor}
              <span class="wp-highlight-indicator" id="wp-bg-color-bar"></span>
            </button>
            <div class="wp-color-popover" id="wp-bg-color-popover">
              <div class="wp-color-grid" id="wp-bg-color-grid"></div>
              <div class="wp-custom-color-row">
                <span>Custom:</span>
                <input type="color" id="wp-custom-bg-color" value="#FEF9C3">
              </div>
              <button type="button" class="wp-color-clear-btn" id="wp-clear-highlight-btn">Remove Highlight</button>
            </div>
          </div>
        </div>

        <div class="wp-toolbar-divider"></div>

        <!-- Alignment -->
        <div class="wp-toolbar-group">
          <button type="button" class="wp-editor-btn" data-command="justifyLeft" title="Align Left">${ICONS.alignLeft}</button>
          <button type="button" class="wp-editor-btn" data-command="justifyCenter" title="Align Center">${ICONS.alignCenter}</button>
          <button type="button" class="wp-editor-btn" data-command="justifyRight" title="Align Right">${ICONS.alignRight}</button>
          <button type="button" class="wp-editor-btn" data-command="justifyFull" title="Justify">${ICONS.alignJustify}</button>
        </div>

        <!-- Lists and Indents -->
        <div class="wp-toolbar-group">
          <button type="button" class="wp-editor-btn" data-command="insertUnorderedList" title="Bulleted List">${ICONS.listUl}</button>
          <button type="button" class="wp-editor-btn" data-command="insertOrderedList" title="Numbered List">${ICONS.listOl}</button>
          <button type="button" class="wp-editor-btn" data-command="outdent" title="Decrease Indent">${ICONS.outdent}</button>
          <button type="button" class="wp-editor-btn" data-command="indent" title="Increase Indent">${ICONS.indent}</button>
        </div>

        <div class="wp-toolbar-divider"></div>

        <!-- Links, Media, Quotes, Divider -->
        <div class="wp-toolbar-group">
          <button type="button" class="wp-editor-btn" id="wp-link-btn" title="Insert / Edit Link">${ICONS.link}</button>
          <button type="button" class="wp-editor-btn" data-command="unlink" title="Remove Link">${ICONS.unlink}</button>
          <button type="button" class="wp-editor-btn" id="wp-insert-image-btn" title="Insert Image">${ICONS.image}</button>
          <button type="button" class="wp-editor-btn" data-command="formatBlock" data-val="blockquote" title="Quote">${ICONS.blockquote}</button>
          <button type="button" class="wp-editor-btn" data-command="insertHorizontalRule" title="Horizontal Divider">${ICONS.hr}</button>
        </div>

        <div class="wp-toolbar-divider"></div>

        <!-- Clean, History & Source Toggle -->
        <div class="wp-toolbar-group">
          <button type="button" class="wp-editor-btn" data-command="removeFormat" title="Clear Formatting">${ICONS.removeFormat}</button>
          <button type="button" class="wp-editor-btn" data-command="undo" title="Undo (Ctrl+Z)">${ICONS.undo}</button>
          <button type="button" class="wp-editor-btn" data-command="redo" title="Redo (Ctrl+Y)">${ICONS.redo}</button>
          <button type="button" class="wp-editor-btn" id="wp-source-toggle-btn" title="Toggle HTML Source">${ICONS.source}</button>
        </div>
      `;

      // 3. Body: Visual contenteditable + Source textarea
      const body = document.createElement('div');
      body.className = 'wp-editor-body';

      this.visual = document.createElement('div');
      this.visual.className = 'wp-editor-visual';
      this.visual.contentEditable = true;
      this.visual.setAttribute('data-placeholder', this.options.placeholder);

      this.source = document.createElement('textarea');
      this.source.className = 'wp-editor-source';
      this.source.spellcheck = false;

      body.appendChild(this.visual);
      body.appendChild(this.source);

      // 4. Status Bar: Path indicator + Word/Char counts
      const statusbar = document.createElement('div');
      statusbar.className = 'wp-editor-statusbar';
      statusbar.innerHTML = `
        <div class="wp-editor-path" id="wp-editor-path">Path: p</div>
        <div class="wp-editor-counts">
          <span id="wp-word-count">0 words</span>
          <span id="wp-char-count">0 characters</span>
        </div>
      `;

      // Assemble
      this.container.appendChild(topbar);
      this.container.appendChild(toolbar);
      this.container.appendChild(body);
      this.container.appendChild(statusbar);

      // Insert into DOM right after textarea
      this.textarea.parentNode.insertBefore(this.container, this.textarea.nextSibling);

      // Populate Color Palettes
      this.populateColorGrids();
    }

    populateColorGrids() {
      const textGrid = this.container.querySelector('#wp-text-color-grid');
      TEXT_COLORS.forEach(c => {
        const swatch = document.createElement('div');
        swatch.className = 'wp-color-swatch';
        swatch.style.backgroundColor = c.hex;
        swatch.title = c.label;
        swatch.addEventListener('click', () => {
          this.applyTextColor(c.hex);
          this.closePopovers();
        });
        textGrid.appendChild(swatch);
      });

      const bgGrid = this.container.querySelector('#wp-bg-color-grid');
      HIGHLIGHT_COLORS.forEach(c => {
        const swatch = document.createElement('div');
        swatch.className = 'wp-color-swatch';
        swatch.style.backgroundColor = c.hex === 'transparent' ? '#FFFFFF' : c.hex;
        if (c.hex === 'transparent') {
          swatch.style.position = 'relative';
          swatch.innerHTML = '<span style="color: red; font-size: 16px; font-weight: bold; line-height: 24px; display: block; text-align: center;">&times;</span>';
        }
        swatch.title = c.label;
        swatch.addEventListener('click', () => {
          this.applyHighlightColor(c.hex);
          this.closePopovers();
        });
        bgGrid.appendChild(swatch);
      });
    }

    bindEvents() {
      // Topbar Mode Switch (Visual vs Text)
      const tabs = this.container.querySelectorAll('.wp-editor-tab');
      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          this.setMode(tab.getAttribute('data-mode'));
        });
      });

      // Media Button
      this.container.querySelector('#wp-add-media-btn').addEventListener('click', () => {
        this.openMediaModal();
      });

      this.container.querySelector('#wp-insert-image-btn').addEventListener('click', () => {
        this.openMediaModal();
      });

      // Link Button
      this.container.querySelector('#wp-link-btn').addEventListener('click', () => {
        this.openLinkModal();
      });

      // HTML Source Toggle
      this.container.querySelector('#wp-source-toggle-btn').addEventListener('click', () => {
        this.setMode(this.mode === 'visual' ? 'source' : 'visual');
      });

      // Standard toolbar command buttons
      const cmdBtns = this.container.querySelectorAll('.wp-editor-btn[data-command]');
      cmdBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          const cmd = btn.getAttribute('data-command');
          const val = btn.getAttribute('data-val') || null;
          this.executeCommand(cmd, val);
        });
      });

      // Toolbar Selects (Format block, Font family, Font size)
      const selects = this.container.querySelectorAll('.wp-editor-select');
      selects.forEach(sel => {
        sel.addEventListener('change', () => {
          const cmd = sel.getAttribute('data-command');
          const val = sel.value;
          if (cmd === 'formatBlock') {
            this.executeCommand('formatBlock', val ? `<${val}>` : '<p>');
          } else if (cmd === 'fontName') {
            if (val) this.applyFontFamily(val);
          } else if (cmd === 'fontSize') {
            if (val) this.applyFontSize(val);
          }
          sel.selectedIndex = 0; // reset selector view
        });
      });

      // Color popover toggles
      const textColorBtn = this.container.querySelector('#wp-text-color-btn');
      const textPopover = this.container.querySelector('#wp-text-color-popover');
      textColorBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.saveSelection();
        const isOpen = textPopover.classList.contains('open');
        this.closePopovers();
        if (!isOpen) textPopover.classList.add('open');
      });

      const bgColorBtn = this.container.querySelector('#wp-bg-color-btn');
      const bgPopover = this.container.querySelector('#wp-bg-color-popover');
      bgColorBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.saveSelection();
        const isOpen = bgPopover.classList.contains('open');
        this.closePopovers();
        if (!isOpen) bgPopover.classList.add('open');
      });

      // Custom color inputs
      this.container.querySelector('#wp-custom-text-color').addEventListener('change', (e) => {
        this.applyTextColor(e.target.value);
        this.closePopovers();
      });

      this.container.querySelector('#wp-custom-bg-color').addEventListener('change', (e) => {
        this.applyHighlightColor(e.target.value);
        this.closePopovers();
      });

      this.container.querySelector('#wp-clear-highlight-btn').addEventListener('click', () => {
        this.applyHighlightColor('transparent');
        this.closePopovers();
      });

      // Close color popovers on outside click
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.wp-color-btn-wrap')) {
          this.closePopovers();
        }
      });

      // Visual Editor changes
      this.visual.addEventListener('input', () => {
        this.syncToTextarea();
        this.updateStatus();
      });

      this.visual.addEventListener('keyup', () => {
        this.syncToTextarea();
        this.updateStatus();
        this.updateActiveToolbarState();
      });

      this.visual.addEventListener('mouseup', () => {
        this.updateStatus();
        this.updateActiveToolbarState();
      });

      // Handle Keyboard Shortcuts (Ctrl+B, Ctrl+I, Ctrl+U, etc.)
      this.visual.addEventListener('keydown', (e) => {
        if (e.ctrlKey || e.metaKey) {
          const key = e.key.toLowerCase();
          if (key === 'b') {
            e.preventDefault();
            this.executeCommand('bold');
          } else if (key === 'i') {
            e.preventDefault();
            this.executeCommand('italic');
          } else if (key === 'u') {
            e.preventDefault();
            this.executeCommand('underline');
          } else if (key === 'z') {
            if (e.shiftKey) {
              e.preventDefault();
              this.executeCommand('redo');
            } else {
              e.preventDefault();
              this.executeCommand('undo');
            }
          } else if (key === 'y') {
            e.preventDefault();
            this.executeCommand('redo');
          }
        }
      });

      // Source Editor changes
      this.source.addEventListener('input', () => {
        this.visual.innerHTML = this.source.value;
        this.textarea.value = this.source.value;
        this.updateStatus();
      });

      // Synchronize on form submit
      const form = this.textarea.closest('form');
      if (form) {
        form.addEventListener('submit', () => {
          if (this.mode === 'source') {
            this.textarea.value = this.source.value.trim();
          } else {
            this.textarea.value = this.visual.innerHTML.trim();
          }
        });
      }
    }

    closePopovers() {
      const popovers = this.container.querySelectorAll('.wp-color-popover');
      popovers.forEach(p => p.classList.remove('open'));
    }

    setMode(mode) {
      if (mode === this.mode) return;
      this.mode = mode;

      const tabs = this.container.querySelectorAll('.wp-editor-tab');
      tabs.forEach(t => t.classList.toggle('active', t.getAttribute('data-mode') === mode));

      const sourceBtn = this.container.querySelector('#wp-source-toggle-btn');

      if (mode === 'source') {
        // Switching to HTML Source Mode
        this.source.value = this.formatHTML(this.visual.innerHTML);
        this.visual.style.display = 'none';
        this.source.style.display = 'block';
        this.source.focus();
        sourceBtn.classList.add('is-active');
      } else {
        // Switching back to Visual Mode
        this.visual.innerHTML = this.source.value;
        this.source.style.display = 'none';
        this.visual.style.display = 'block';
        this.visual.focus();
        sourceBtn.classList.remove('is-active');
      }

      this.syncToTextarea();
      this.updateStatus();
    }

    executeCommand(cmd, val = null) {
      if (this.mode === 'source') {
        alert('Please switch to "Visual" mode to use interactive formatting buttons.');
        return;
      }
      this.visual.focus();
      document.execCommand(cmd, false, val);
      this.syncToTextarea();
      this.updateStatus();
      this.updateActiveToolbarState();
    }

    saveSelection() {
      const sel = window.getSelection();
      if (sel.getRangeAt && sel.rangeCount) {
        this.savedSelection = sel.getRangeAt(0);
      }
    }

    restoreSelection() {
      if (this.savedSelection) {
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(this.savedSelection);
      }
    }

    applyTextColor(color) {
      this.restoreSelection();
      this.executeCommand('foreColor', color);
      const bar = this.container.querySelector('#wp-text-color-bar');
      if (bar) bar.style.backgroundColor = color;
    }

    applyHighlightColor(color) {
      this.restoreSelection();
      this.executeCommand('hiliteColor', color);
      const bar = this.container.querySelector('#wp-bg-color-bar');
      if (bar) bar.style.backgroundColor = color === 'transparent' ? '#CBD5E1' : color;
    }

    applyFontFamily(font) {
      this.visual.focus();
      this.executeCommand('fontName', font);
    }

    applyFontSize(size) {
      this.visual.focus();
      const sel = window.getSelection();
      if (!sel.isCollapsed && sel.rangeCount) {
        const span = document.createElement('span');
        span.style.fontSize = size;
        const range = sel.getRangeAt(0);
        span.appendChild(range.extractContents());
        range.insertNode(span);
        this.syncToTextarea();
      }
    }

    syncToTextarea() {
      if (this.mode === 'visual') {
        this.textarea.value = this.visual.innerHTML.trim();
      } else {
        this.textarea.value = this.source.value.trim();
      }
    }

    updateStatus() {
      // Word count & Character count
      const text = this.visual.innerText || '';
      const words = text.trim() ? text.trim().split(/\s+/).length : 0;
      const chars = text.length;

      const wordSpan = this.container.querySelector('#wp-word-count');
      const charSpan = this.container.querySelector('#wp-char-count');
      if (wordSpan) wordSpan.textContent = `${words} ${words === 1 ? 'word' : 'words'}`;
      if (charSpan) charSpan.textContent = `${chars} ${chars === 1 ? 'character' : 'characters'}`;

      // Current DOM Path
      const pathEl = this.container.querySelector('#wp-editor-path');
      if (pathEl) {
        const sel = window.getSelection();
        if (sel.rangeCount && this.visual.contains(sel.anchorNode)) {
          let node = sel.anchorNode;
          if (node.nodeType === Node.TEXT_NODE) node = node.parentNode;
          const tags = [];
          while (node && node !== this.visual && node.tagName) {
            tags.unshift(node.tagName.toLowerCase());
            node = node.parentNode;
          }
          pathEl.textContent = 'Path: ' + (tags.length ? tags.join(' > ') : 'p');
        } else {
          pathEl.textContent = 'Path: p';
        }
      }
    }

    updateActiveToolbarState() {
      const commands = ['bold', 'italic', 'underline', 'strikeThrough', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', 'insertUnorderedList', 'insertOrderedList'];
      commands.forEach(cmd => {
        const btn = this.container.querySelector(`.wp-editor-btn[data-command="${cmd}"]`);
        if (btn) {
          try {
            const isActive = document.queryCommandState(cmd);
            btn.classList.toggle('is-active', isActive);
          } catch (e) {}
        }
      });
    }

    formatHTML(html) {
      // Clean indentation formatting for Source View
      let formatted = '';
      const reg = /(>)(<)(\/*)/g;
      html = html.replace(reg, '$1\r\n$2$3');
      let pad = 0;
      html.split('\r\n').forEach(node => {
        let indent = 0;
        if (node.match(/.+<\/\w[^>]*>$/)) {
          indent = 0;
        } else if (node.match(/^<\/\w/)) {
          if (pad !== 0) pad -= 1;
        } else if (node.match(/^<\w[^>]*[^\/]>.*$/)) {
          indent = 1;
        } else {
          indent = 0;
        }
        let padding = '';
        for (let i = 0; i < pad; i++) padding += '  ';
        formatted += padding + node + '\r\n';
        pad += indent;
      });
      return formatted.trim();
    }

    // ==========================================
    // Link Insertion Modal
    // ==========================================
    openLinkModal() {
      this.saveSelection();
      let selectedText = '';
      let existingHref = '';
      let existingTitle = '';
      let existingTarget = false;

      const sel = window.getSelection();
      let parentLink = null;
      if (sel.rangeCount) {
        selectedText = sel.toString();
        let node = sel.anchorNode;
        if (node && node.nodeType === Node.TEXT_NODE) node = node.parentNode;
        parentLink = node ? node.closest('a') : null;
        if (parentLink) {
          existingHref = parentLink.getAttribute('href') || '';
          existingTitle = parentLink.getAttribute('title') || '';
          existingTarget = parentLink.getAttribute('target') === '_blank';
          if (!selectedText) selectedText = parentLink.innerText;
        }
      }

      const modal = document.createElement('div');
      modal.className = 'wp-modal-backdrop';
      modal.innerHTML = `
        <div class="wp-modal-window">
          <div class="wp-modal-header">
            <h3 class="wp-modal-title">${parentLink ? 'Edit Link' : 'Insert Link'}</h3>
            <button type="button" class="wp-modal-close">&times;</button>
          </div>
          <div class="wp-modal-body">
            <div class="wp-modal-input-group">
              <label>URL *</label>
              <input type="text" id="wp-link-url" class="wp-modal-input" placeholder="https://example.com" value="${existingHref}">
            </div>
            <div class="wp-modal-input-group">
              <label>Text to display</label>
              <input type="text" id="wp-link-text" class="wp-modal-input" placeholder="Link title" value="${selectedText}">
            </div>
            <div class="wp-modal-input-group">
              <label>Link Title (Tooltip)</label>
              <input type="text" id="wp-link-title" class="wp-modal-input" placeholder="Hover tooltip (optional)" value="${existingTitle}">
            </div>
            <label class="wp-modal-checkbox">
              <input type="checkbox" id="wp-link-target" ${existingTarget ? 'checked' : ''}>
              <span>Open link in a new tab</span>
            </label>
          </div>
          <div class="wp-modal-footer">
            <button type="button" class="btn btn-outline wp-modal-cancel" style="padding: 0.45rem 1.25rem; font-size: 0.85rem;">Cancel</button>
            <button type="button" class="btn btn-primary wp-modal-save" style="padding: 0.45rem 1.5rem; font-size: 0.85rem;">Apply Link</button>
          </div>
        </div>
      `;

      document.body.appendChild(modal);

      const close = () => { modal.remove(); };
      modal.querySelector('.wp-modal-close').addEventListener('click', close);
      modal.querySelector('.wp-modal-cancel').addEventListener('click', close);
      modal.addEventListener('click', (e) => { if (e.target === modal) close(); });

      const urlInput = modal.querySelector('#wp-link-url');
      urlInput.focus();

      modal.querySelector('.wp-modal-save').addEventListener('click', () => {
        let url = urlInput.value.trim();
        const text = modal.querySelector('#wp-link-text').value.trim();
        const title = modal.querySelector('#wp-link-title').value.trim();
        const openNew = modal.querySelector('#wp-link-target').checked;

        if (!url) {
          alert('Please enter a valid URL.');
          return;
        }

        // Auto prepend https:// if missing protocol and not relative
        if (!url.startsWith('http://') && !url.startsWith('https://') && !url.startsWith('/') && !url.startsWith('#') && !url.startsWith('mailto:') && !url.startsWith('tel:')) {
          url = 'https://' + url;
        }

        close();
        this.restoreSelection();

        if (parentLink) {
          parentLink.setAttribute('href', url);
          if (title) parentLink.setAttribute('title', title); else parentLink.removeAttribute('title');
          if (openNew) {
            parentLink.setAttribute('target', '_blank');
            parentLink.setAttribute('rel', 'noopener noreferrer');
          } else {
            parentLink.removeAttribute('target');
            parentLink.removeAttribute('rel');
          }
          if (text) parentLink.textContent = text;
        } else {
          const a = document.createElement('a');
          a.href = url;
          a.textContent = text || url;
          if (title) a.title = title;
          if (openNew) {
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
          }

          const sel = window.getSelection();
          if (sel.rangeCount) {
            const range = sel.getRangeAt(0);
            range.deleteContents();
            range.insertNode(a);
          } else {
            this.visual.appendChild(a);
          }
        }

        this.syncToTextarea();
        this.updateStatus();
      });
    }

    // ==========================================
    // Media / Image Modal
    // ==========================================
    openMediaModal() {
      this.saveSelection();

      const modal = document.createElement('div');
      modal.className = 'wp-modal-backdrop';
      modal.innerHTML = `
        <div class="wp-modal-window" style="max-width: 680px;">
          <div class="wp-modal-header">
            <h3 class="wp-modal-title">Add Media / Insert Image</h3>
            <button type="button" class="wp-modal-close">&times;</button>
          </div>
          <div class="wp-modal-tabs">
            <button type="button" class="wp-modal-tab active" data-tab="upload">Upload Files</button>
            <button type="button" class="wp-modal-tab" data-tab="url">Insert from URL</button>
            <button type="button" class="wp-modal-tab" data-tab="library">Media Library</button>
          </div>
          <div class="wp-modal-body">
            <!-- Tab 1: Upload Files -->
            <div id="wp-media-tab-upload">
              <div class="wp-upload-dropzone" id="wp-dropzone">
                ${ICONS.uploadCloud}
                <p><strong>Click to choose a file</strong> or drag and drop image here</p>
                <span>Supported: JPG, PNG, WEBP (Max 4MB)</span>
                <input type="file" id="wp-file-input" accept="image/jpeg,image/png,image/webp" style="display: none;">
              </div>
              <div id="wp-upload-status" style="margin-top: 1rem; font-size: 0.85rem; color: var(--color-navy); display: none;"></div>
            </div>

            <!-- Tab 2: Insert from URL -->
            <div id="wp-media-tab-url" style="display: none;">
              <div class="wp-modal-input-group">
                <label>Image URL *</label>
                <input type="text" id="wp-img-url" class="wp-modal-input" placeholder="https://example.com/image.jpg or /assets/images/photo.png">
              </div>
              <div class="wp-modal-input-group">
                <label>Alt Text (Alternative text for accessibility)</label>
                <input type="text" id="wp-img-alt" class="wp-modal-input" placeholder="Describe the image...">
              </div>
              <div class="wp-modal-input-group">
                <label>Image Alignment</label>
                <select id="wp-img-align" class="wp-modal-input">
                  <option value="align-center">Center (Default)</option>
                  <option value="align-left">Left Align (Text wraps right)</option>
                  <option value="align-right">Right Align (Text wraps left)</option>
                  <option value="none">None (Inline)</option>
                </select>
              </div>
            </div>

            <!-- Tab 3: Media Library -->
            <div id="wp-media-tab-library" style="display: none;">
              <p style="font-size: 0.82rem; color: #64748B; margin-bottom: 0.75rem;">Click an existing asset to select it:</p>
              <div class="wp-media-grid" id="wp-media-library-grid">
                <div style="grid-column: 1/-1; text-align: center; color: #94A3B8; padding: 2rem;">Loading school media library...</div>
              </div>
            </div>
          </div>
          <div class="wp-modal-footer">
            <button type="button" class="btn btn-outline wp-modal-cancel" style="padding: 0.45rem 1.25rem; font-size: 0.85rem;">Cancel</button>
            <button type="button" class="btn btn-primary wp-modal-insert" style="padding: 0.45rem 1.5rem; font-size: 0.85rem;">Insert into Post</button>
          </div>
        </div>
      `;

      document.body.appendChild(modal);

      const close = () => { modal.remove(); };
      modal.querySelector('.wp-modal-close').addEventListener('click', close);
      modal.querySelector('.wp-modal-cancel').addEventListener('click', close);
      modal.addEventListener('click', (e) => { if (e.target === modal) close(); });

      // Tab Switching
      let currentTab = 'upload';
      let selectedLibraryUrl = '';
      let uploadedImageUrl = '';

      const tabs = modal.querySelectorAll('.wp-modal-tab');
      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          tabs.forEach(t => t.classList.remove('active'));
          tab.classList.add('active');
          currentTab = tab.getAttribute('data-tab');

          modal.querySelector('#wp-media-tab-upload').style.display = currentTab === 'upload' ? 'block' : 'none';
          modal.querySelector('#wp-media-tab-url').style.display = currentTab === 'url' ? 'block' : 'none';
          modal.querySelector('#wp-media-tab-library').style.display = currentTab === 'library' ? 'block' : 'none';

          if (currentTab === 'library') {
            this.loadMediaLibrary(modal, (url) => {
              selectedLibraryUrl = url;
            });
          }
        });
      });

      // Dropzone & File Input upload handling
      const dropzone = modal.querySelector('#wp-dropzone');
      const fileInput = modal.querySelector('#wp-file-input');
      const statusEl = modal.querySelector('#wp-upload-status');

      dropzone.addEventListener('click', () => fileInput.click());
      dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
      dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
      dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
          handleFile(e.dataTransfer.files[0]);
        }
      });

      fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
          handleFile(fileInput.files[0]);
        }
      });

      const handleFile = (file) => {
        statusEl.style.display = 'block';
        statusEl.innerHTML = `<em>Uploading ${file.name}...</em>`;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('csrf_token', this.options.csrfToken);

        fetch(this.options.uploadUrl, {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success && data.url) {
            uploadedImageUrl = data.url;
            statusEl.innerHTML = `<span style="color: #059669; font-weight: 600;">&check; Uploaded successfully:</span> <code style="font-size: 0.8rem;">${data.url}</code>`;
            // Switch to URL view with prefilled url
            modal.querySelector('#wp-img-url').value = data.url;
            modal.querySelector('#wp-img-alt').value = file.name.replace(/\.[^/.]+$/, '');
            tabs[1].click(); // switch to URL tab for caption & alignment
          } else {
            statusEl.innerHTML = `<span style="color: #DC2626;">&cross; Upload failed: ${data.error || 'Server error'}</span>`;
          }
        })
        .catch(err => {
          statusEl.innerHTML = `<span style="color: #DC2626;">&cross; Upload error: ${err.message}</span>`;
        });
      };

      // Insert button handler
      modal.querySelector('.wp-modal-insert').addEventListener('click', () => {
        let finalUrl = '';
        let alt = modal.querySelector('#wp-img-alt').value.trim();
        let align = modal.querySelector('#wp-img-align').value;

        if (currentTab === 'upload' && uploadedImageUrl) {
          finalUrl = uploadedImageUrl;
        } else if (currentTab === 'url') {
          finalUrl = modal.querySelector('#wp-img-url').value.trim();
        } else if (currentTab === 'library' && selectedLibraryUrl) {
          finalUrl = selectedLibraryUrl;
        }

        if (!finalUrl) {
          alert('Please select or upload an image first.');
          return;
        }

        close();
        this.restoreSelection();

        const img = document.createElement('img');
        img.src = finalUrl;
        img.alt = alt || 'Article image';
        if (align && align !== 'none') {
          img.className = align;
        }

        const sel = window.getSelection();
        if (sel.rangeCount) {
          const range = sel.getRangeAt(0);
          range.deleteContents();
          range.insertNode(img);
        } else {
          this.visual.appendChild(img);
        }

        // Add a trailing paragraph if at bottom
        const p = document.createElement('p');
        p.innerHTML = '<br>';
        img.parentNode.insertBefore(p, img.nextSibling);

        this.syncToTextarea();
        this.updateStatus();
      });
    }

    loadMediaLibrary(modal, onSelect) {
      const grid = modal.querySelector('#wp-media-library-grid');
      fetch(this.options.mediaLibraryUrl)
        .then(res => res.json())
        .then(data => {
          grid.innerHTML = '';
          const items = [];
          if (data.media && data.media.length) {
            items.push(...data.media);
          }
          if (data.static && data.static.length) {
            items.push(...data.static);
          }

          if (!items.length) {
            grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #94A3B8; padding: 2rem;">No media library files found yet. Use "Upload Files" to add new images.</div>';
            return;
          }

          items.forEach(item => {
            const el = document.createElement('div');
            el.className = 'wp-media-item';
            el.innerHTML = `
              <img src="${item.public_url}" alt="${item.file_name}" loading="lazy">
              <span class="wp-media-item-check">&check;</span>
            `;
            el.addEventListener('click', () => {
              grid.querySelectorAll('.wp-media-item').forEach(i => i.classList.remove('selected'));
              el.classList.add('selected');
              modal.querySelector('#wp-img-url').value = item.public_url;
              modal.querySelector('#wp-img-alt').value = item.file_name.replace(/\.[^/.]+$/, '');
              onSelect(item.public_url);
            });
            grid.appendChild(el);
          });
        })
        .catch(() => {
          grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #DC2626; padding: 2rem;">Could not load media library.</div>';
        });
    }
  }

  // Auto-initialize when DOM is ready
  window.ZuvioRichEditor = ZuvioRichEditor;

  document.addEventListener('DOMContentLoaded', () => {
    const blogTextarea = document.querySelector('textarea[name="content"]');
    if (blogTextarea) {
      window.editorInstance = new ZuvioRichEditor(blogTextarea);
    }
  });
})();
