const mix = require('laravel-mix');

// Compile NobleUI CSS and JS
mix.copyDirectory('resources/fonts', 'public/fonts')  // Fonts
   .copyDirectory('resources/images', 'public/images')  // Images
   .postCss('resources/css/app.css', 'public/css')  // Plain CSS for NobleUI
   .js('resources/js/vendor.bundle.base.js', 'public/js')  // Vendor JS
   .js('resources/js/dashboard.js', 'public/js')  // Custom dashboard JS
   .sourceMaps();

// Optionally, version files for cache-busting in production
if (mix.inProduction()) {
    mix.version();
}