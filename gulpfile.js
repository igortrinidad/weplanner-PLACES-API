var elixir  = require('laravel-elixir'),
    gulp    = require('gulp'),
    htmlmin = require('gulp-htmlmin');

elixir.extend('compress', function() {
    new elixir.Task('compress', function() {
        return gulp.src('./storage/framework/views/*')
            .pipe(htmlmin({
                collapseWhitespace:    true,
                removeAttributeQuotes: true,
                removeComments:        true,
                minifyJS:              true,
            }))
            .pipe(gulp.dest('./storage/framework/views/'));
    })
    .watch('./storage/framework/views/*');
});

elixir(function(mix) {
    mix.compress();

    //STYLES
    mix.styles([
        '../../../node_modules/unitegallery/dist/css/unite-gallery.css',
        '../../../node_modules/unitegallery/dist/themes/default/ug-theme-default.css',
        'animate.css',
        'bootstrap.min.css',
        'general.css',
        'ionicons.min.css',
        'magnific-popup.css',
        'owl.carousel.css',
        'style.css',
        'helpers.css',

    ], 'public/build/landing/css/vendors.css');

    //JS
    mix.scripts([
        'jquery.min.js',
        'bootstrap.min.js',
        'html5shiv.min.js',
        'jquery.magnific-popup.min.js',
        'jquery.ajaxchimp.min.js',
        'jquery.counterup.min.js',
        'jquery.form.js',
        'jquery.validate.min.js',
        'owl.carousel.min.js',
        'respond.min.js',
        'waypoints.min.js',
        'wow.min.js',
        '../../../node_modules/unitegallery/dist/js/unitegallery.min.js',
        '../../../node_modules/unitegallery/dist/themes/tiles/ug-theme-tiles.js',
        'init.js'

    ], 'public/build/landing/js/vendors.js');

    mix.version([
        'public/build/landing/js/vendors.js', 
        'public/build/landing/css/vendors.css'
    ]);

});