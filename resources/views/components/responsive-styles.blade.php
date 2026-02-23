<!-- Responsive CSS -->
<style>
    /* Mobile First Approach */
    
    /* Tablets and up (768px) */
    @media (max-width: 767px) {
        .responsive-grid-2 {
            grid-template-columns: 1fr !important;
        }
        
        .responsive-grid-4 {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .responsive-grid-auto {
            grid-template-columns: 1fr !important;
        }
        
        .responsive-flex-reverse {
            flex-direction: column !important;
        }
        
        .sidebar-sticky {
            position: static !important;
            top: auto !important;
        }
        
        h1 {
            font-size: 1.8rem !important;
        }
        
        h2 {
            font-size: 1.5rem !important;
        }
        
        h3 {
            font-size: 1.2rem !important;
        }
        
        .full-width-mobile {
            width: 100% !important;
        }
    }
    
    /* Small phones (480px) */
    @media (max-width: 479px) {
        .responsive-grid-4 {
            grid-template-columns: 1fr !important;
        }
        
        h1 {
            font-size: 1.5rem !important;
        }
        
        .max-width-full {
            max-width: 100% !important;
        }
        
        .px-mobile-2 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
    }
    
    /* Tablets (768px to 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
        .responsive-grid-2 {
            grid-template-columns: 1fr 1fr !important;
        }
    }
    
    /* Large screens (1025px+) */
    @media (min-width: 1025px) {
        .responsive-grid-2 {
            grid-template-columns: 1fr 1fr !important;
        }
        
        .responsive-grid-4 {
            grid-template-columns: repeat(4, 1fr) !important;
        }
        
        .responsive-grid-auto {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
        }
        
        .responsive-flex-reverse {
            flex-direction: row !important;
        }
        
        .sidebar-sticky {
            position: sticky !important;
            top: 120px !important;
        }
    }
</style>
