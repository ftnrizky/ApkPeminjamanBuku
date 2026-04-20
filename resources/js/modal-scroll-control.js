/**
 * Modal Scroll Control System
 * Mengendalikan scroll body saat modal terbuka
 * Kompatibel: Desktop, iOS, Android
 * 
 * Fitur:
 * - Disable body scroll saat modal open
 * - Hilangkan bounce/overscroll effect
 * - Smooth transitions
 * - Focus trap
 * - ESC key to close
 * - Responsive untuk semua device
 */

class ModalScrollControl {
    constructor() {
        this.activeModals = [];
        this.scrollPosition = 0;
        this.viewportWidth = window.innerWidth;
        this.isInitialized = false;
        this.init();
    }

    /**
     * Initialize event listeners
     */
    init() {
        if (this.isInitialized) return;
        
        // ESC key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeLastModal();
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            this.viewportWidth = window.innerWidth;
        });

        // Prevent scroll on body
        document.addEventListener('touchmove', (e) => {
            if (this.isModalOpen() && !this.isElementScrollable(e.target)) {
                e.preventDefault();
            }
        }, { passive: false });

        this.isInitialized = true;
    }

    /**
     * Open modal dengan scroll control
     * @param {string|HTMLElement} modalId - ID atau element modal
     * @param {Object} options - Konfigurasi
     */
    openModal(modalId, options = {}) {
        const modal = typeof modalId === 'string' 
            ? document.getElementById(modalId) 
            : modalId;

        if (!modal) {
            console.warn(`Modal "${modalId}" not found`);
            return false;
        }

        // Default options
        const config = {
            closeOnBackdrop: true,
            closeOnEscape: true,
            disableBodyScroll: true,
            scrollAnimation: true,
            onOpen: null,
            onClose: null,
            ...options
        };

        // Jika ada modal yang sedang aktif, track sebelumnya
        if (this.activeModals.length === 0) {
            this.disableBodyScroll();
        }

        this.activeModals.push(modal);
        
        // Add active class
        modal.classList.remove('hidden');
        modal.classList.add('modal-active');
        
        // Backdrop
        const backdrop = modal.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.classList.add('modal-active');
            if (config.closeOnBackdrop) {
                backdrop.addEventListener('click', () => this.closeModal(modal));
            }
        }

        // Scroll to top of modal content
        const content = modal.querySelector('.modal-content');
        if (content) {
            setTimeout(() => {
                content.scrollTop = 0;
            }, 10);
        }

        // Callback
        if (typeof config.onOpen === 'function') {
            config.onOpen(modal);
        }

        // Add to window for access via inline onclick
        window.currentOpenModal = modal;

        return true;
    }

    /**
     * Close modal dengan scroll control
     * @param {string|HTMLElement} modalId - ID atau element modal
     * @param {Object} options - Konfigurasi
     */
    closeModal(modalId, options = {}) {
        const modal = typeof modalId === 'string'
            ? document.getElementById(modalId)
            : modalId;

        if (!modal) {
            console.warn(`Modal "${modalId}" not found`);
            return false;
        }

        const config = {
            animate: true,
            onClose: null,
            ...options
        };

        // Remove dari active modals array
        this.activeModals = this.activeModals.filter(m => m !== modal);

        // Animate closing
        if (config.animate) {
            const content = modal.querySelector('.modal-content');
            if (content) {
                content.classList.add('closing');
                setTimeout(() => {
                    this.finishClosingModal(modal);
                }, 200);
            } else {
                this.finishClosingModal(modal);
            }
        } else {
            this.finishClosingModal(modal);
        }

        // Callback
        if (typeof config.onClose === 'function') {
            config.onClose(modal);
        }

        return true;
    }

    /**
     * Finish closing modal logic
     */
    finishClosingModal(modal) {
        const content = modal.querySelector('.modal-content');
        if (content) {
            content.classList.remove('closing');
        }

        modal.classList.add('hidden');
        modal.classList.remove('modal-active');

        const backdrop = modal.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.classList.remove('modal-active');
        }

        // Jika tidak ada modal aktif, enable scroll kembali
        if (this.activeModals.length === 0) {
            this.enableBodyScroll();
        }
    }

    /**
     * Close modal terakhir yang terbuka
     */
    closeLastModal() {
        if (this.activeModals.length > 0) {
            const lastModal = this.activeModals[this.activeModals.length - 1];
            this.closeModal(lastModal);
        }
    }

    /**
     * Check apakah ada modal yang terbuka
     */
    isModalOpen() {
        return this.activeModals.length > 0;
    }

    /**
     * Disable body scroll
     */
    disableBodyScroll() {
        // Save scroll position
        this.scrollPosition = window.scrollY || document.documentElement.scrollTop;

        // Add class ke body dan html
        document.body.classList.add('modal-open');
        document.documentElement.classList.add('modal-open');

        // Set body style
        document.body.style.overflow = 'hidden';
        document.body.style.height = '100vh';
        document.body.style.position = 'fixed';
        document.body.style.width = '100%';
        document.body.style.top = `-${this.scrollPosition}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';

        // Prevent scrollbar jitter on desktop
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        if (scrollbarWidth > 0) {
            document.body.style.paddingRight = scrollbarWidth + 'px';
        }
    }

    /**
     * Enable body scroll
     */
    enableBodyScroll() {
        // Remove class
        document.body.classList.remove('modal-open');
        document.documentElement.classList.remove('modal-open');

        // Reset body style
        document.body.style.overflow = '';
        document.body.style.height = '';
        document.body.style.position = '';
        document.body.style.width = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.paddingRight = '';

        // Restore scroll position
        window.scrollTo(0, this.scrollPosition);
    }

    /**
     * Check apakah element bisa discroll
     */
    isElementScrollable(element) {
        // Check apakah element adalah modal content atau child-nya
        let current = element;
        while (current) {
            if (current.classList && current.classList.contains('modal-content')) {
                return true;
            }
            if (current.classList && current.classList.contains('modal')) {
                return false;
            }
            current = current.parentElement;
        }
        return false;
    }

    /**
     * Get all active modals
     */
    getActiveModals() {
        return this.activeModals;
    }

    /**
     * Close all modals
     */
    closeAllModals() {
        const modals = [...this.activeModals];
        modals.forEach(modal => {
            this.closeModal(modal);
        });
    }

    /**
     * Toggle modal (open jika hidden, close jika open)
     */
    toggleModal(modalId, options = {}) {
        const modal = typeof modalId === 'string'
            ? document.getElementById(modalId)
            : modalId;

        if (!modal) {
            console.warn(`Modal "${modalId}" not found`);
            return false;
        }

        const isOpen = modal.classList.contains('modal-active');
        
        if (isOpen) {
            return this.closeModal(modal, options);
        } else {
            return this.openModal(modal, options);
        }
    }

    /**
     * Set modal scroll position
     */
    setModalScrollTop(modalId, position = 0) {
        const modal = typeof modalId === 'string'
            ? document.getElementById(modalId)
            : modalId;

        if (!modal) return;

        const content = modal.querySelector('.modal-content');
        if (content) {
            content.scrollTop = position;
        }
    }

    /**
     * Get modal scroll position
     */
    getModalScrollTop(modalId) {
        const modal = typeof modalId === 'string'
            ? document.getElementById(modalId)
            : modalId;

        if (!modal) return 0;

        const content = modal.querySelector('.modal-content');
        return content ? content.scrollTop : 0;
    }
}

// Initialize global instance
const modalScroll = new ModalScrollControl();

/**
 * Legacy support functions untuk kompatibilitas dengan kode lama
 */
function toggleModal(modalId, options = {}) {
    return modalScroll.toggleModal(modalId, options);
}

function openModal(modalId, options = {}) {
    return modalScroll.openModal(modalId, options);
}

function closeModal(modalId, options = {}) {
    return modalScroll.closeModal(modalId, options);
}

function closeAllModals() {
    return modalScroll.closeAllModals();
}

// Export untuk module support
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModalScrollControl;
}

console.log('✅ Modal Scroll Control System initialized');
