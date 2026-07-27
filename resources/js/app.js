import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.guestAuthNavigation = () => ({
    activeAuth: window.location.pathname.endsWith('/register') ? 'register' : 'login',
    loading: false,

    init() {
        window.addEventListener('popstate', () => {
            this.loadAuthPage(window.location.href, false);
        });
    },

    async switchAuth(url, target) {
        this.activeAuth = target;
        await this.loadAuthPage(url, true);
    },

    async loadAuthPage(url, pushHistory) {
        if (this.loading || `${window.location.origin}${window.location.pathname}` === url) {
            return;
        }

        this.loading = true;

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) {
                window.location.assign(url);
                return;
            }

            const html = await response.text();
            const nextDocument = new DOMParser().parseFromString(html, 'text/html');
            const nextContent = nextDocument.querySelector('[data-auth-content]');
            const currentContent = document.querySelector('[data-auth-content]');

            if (!nextContent || !currentContent) {
                window.location.assign(url);
                return;
            }

            currentContent.innerHTML = nextContent.innerHTML;

            ['kicker', 'heading', 'description'].forEach((part) => {
                const current = document.querySelector(`[data-auth-${part}]`);
                const next = nextDocument.querySelector(`[data-auth-${part}]`);

                if (current && next) {
                    current.textContent = next.textContent;
                }
            });

            this.activeAuth = url.endsWith('/register') ? 'register' : 'login';

            if (pushHistory) {
                window.history.pushState({}, '', url);
            }

            window.Alpine.initTree(currentContent);
        } catch (error) {
            window.location.assign(url);
        } finally {
            this.loading = false;
        }
    },
});

Alpine.start();
