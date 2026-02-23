<!-- Color System & Global Styles -->
<style>
    :root {
        /* Primary Colors */
        --primary-gradient: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
        --primary-bright: #47F55B;
        --primary-dark: #07d65d;
        --primary-darker: #0a9b3a;
        --primary-light: #f0fdf4;
        
        /* Text Colors */
        --text-primary: #0f172a;
        --text-secondary: #4a5568;
        --text-tertiary: #999;
        
        /* Status Colors */
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #dc2626;
        --info: #3b82f6;
        
        /* Shadows */
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 8px 16px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 12px 24px rgba(7, 214, 93, 0.25);
    }

    body {
        color: var(--text-primary);
    }

    /* Link Colors */
    a {
        color: var(--primary-darker);
        transition: color 0.3s ease;
    }

    a:hover {
        color: var(--primary-dark);
    }

    /* Selection */
    ::selection {
        background: var(--primary-light);
        color: var(--primary-darker);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-dark);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-darker);
    }
</style>
