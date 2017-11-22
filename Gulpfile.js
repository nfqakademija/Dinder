'use strict';

var gulp    = require('gulp');
var sass    = require('gulp-sass');
var concat  = require('gulp-concat');
var uglify  = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');

var dir = {
    assets: './src/AppBundle/Resources/',
    dist: './web/',
    npm: './node_modules/',
};

gulp.task('sass', function() {
    gulp.src(dir.assets + 'style/main.scss')
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(concat('style.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});

gulp.task('minify-css', function() {
    gulp.src([
        // Template CSS Style
        dir.assets + 'style/style.css',
        // Font Awesome
        dir.assets + 'style/font-awesome.css',
        // Flat Icon
        dir.assets + 'style/flaticon.css',
        // Et Line Fonts
        dir.assets + 'style/et-line-fonts.css',
        // Menu Drop Down
        dir.assets + 'style/forest-menu.css',
        // Animation
        dir.assets + 'style/animate.min.css',
        // Select Options
        dir.assets + 'style/select2.min.css',
        // noUiSlider
        dir.assets + 'style/nouislider.min.css',
        // Listing Slider
        dir.assets + 'style/slider.css',
        // Owl carousel
        dir.assets + 'style/owl.carousel.css',
        dir.assets + 'style/owl.theme.css',
        // Responsive Media
        dir.assets + 'style/responsive-media.css',
        // Template Color
        dir.assets + 'style/defualt.css',
        // Image Collection
        dir.assets + 'style/image-collection.css',
        ])
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(concat('template.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});

gulp.task('scripts', function() {
    gulp.src([
            //Third party assets
            dir.npm + 'jquery/dist/jquery.min.js',
            dir.npm + 'bootstrap-sass/assets/javascripts/bootstrap.min.js',

            // Template JS files
            dir.assets + 'scripts/modernizr.js',
            // Jquery Easing
            dir.assets + 'scripts/easing.js',
            // Menu Hover
            dir.assets + 'scripts/forest-megamenu.js',
            // Jquery Appear Plugin
            dir.assets + 'scripts/jquery.appear.min.js',
            // Numbers Animation
            dir.assets + 'scripts/jquery.countTo.js',
            // Jquery Parallex
            dir.assets + 'scripts/jquery.smoothscroll.js',
            // Jquery Select Options
            dir.assets + 'scripts/select2.min.js',
            // noUiSlider
            dir.assets + 'scripts/nouislider.all.min.js',
            // Carousel Slider
            dir.assets + 'scripts/carousel.min.js',
            dir.assets + 'scripts/slide.js',
            // Image Loaded
            dir.assets + 'scripts/imagesloaded.js',
            dir.assets + 'scripts/isotope.min.js',
            // CheckBoxes
            dir.assets + 'scripts/icheck.min.js',
            // Jquery Migration
            dir.assets + 'scripts/jquery-migrate.min.js',
            // Sticky Bar
            dir.assets + 'scripts/theia-sticky-sidebar.js',
            // Template Core JS
            dir.assets + 'scripts/custom.js',

            // Main JS file
            dir.assets + 'scripts/main.js'
        ])
        .pipe(concat('script.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js'));
});

gulp.task('images', function() {
    gulp.src([
            dir.assets + 'images/**'
        ])
        .pipe(gulp.dest(dir.dist + 'images'));
});

gulp.task('fonts', function() {
    gulp.src([
            dir.npm + 'bootstrap-sass/assets/fonts/**',

            // Template fonts
            dir.assets + 'fonts/**'
        ])
        .pipe(gulp.dest(dir.dist + 'fonts'));
});

gulp.task('default', ['sass', 'minify-css', 'scripts', 'fonts', 'images']);
