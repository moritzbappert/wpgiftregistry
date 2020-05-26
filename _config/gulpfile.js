// Include Gulp
var gulp = require('gulp');

// Include Plugins
var eslint = require('gulp-eslint');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var plumber = require('gulp-plumber');
var gulpUtil = require('gulp-util');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var pixrem = require('pixrem');
var cssnano = require('cssnano');

// make noise on js and scss errors
function errorHandler() {
    gulpUtil.beep();
    return true;
}

// Lint JS-Files
gulp.task('lint', function () {
    return gulp
        .src('../**.js')
        .pipe(eslint({
            configFile: '.eslintrc.js'
        }))
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
});

// Concatenate & Minify JS Admin
gulp.task('scripts-admin', function () {
    return gulp
        .src([
            '../admin/src/js/globals/**.js',
            '../admin/src/js/**.js',
            '!../admin/src/js/_example.js'
        ])
        .pipe(concat('main-admin.js'))
        .pipe(gulp.dest('../admin/js'))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(plumber(errorHandler))
        .pipe(uglify())
        .pipe(plumber.stop())
        .pipe(gulp.dest('../admin/js'));
});

// Concatenate & Minify JS
gulp.task('scripts', function () {
    return gulp
        .src([
            '../public/src/js/globals/**.js',
            '../public/src/js/**.js'
        ])
        .pipe(concat('main.js'))
        .pipe(gulp.dest('../public/js'))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(plumber(errorHandler))
        .pipe(uglify())
        .pipe(plumber.stop())
        .pipe(gulp.dest('../public/js'));
});

// Copy & Minify Vendor JS Admin
gulp.task('scripts-vendor-admin', function () {
    return gulp
        .src([
            '../admin/src/js/vendor/**.js'
        ])
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('../admin/js/vendor'))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(plumber(errorHandler))
        .pipe(uglify())
        .pipe(plumber.stop())
        .pipe(gulp.dest('../admin/js/vendor'));
});

// Copy & Minify Vendor JS
gulp.task('scripts-vendor', function () {
    return gulp
        .src([
            '../public/src/js/vendor/**.js'
        ])
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('../public/js/vendor'))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(plumber(errorHandler))
        .pipe(uglify())
        .pipe(plumber.stop())
        .pipe(gulp.dest('../public/js/vendor'));
});

// Compile Sass
gulp.task('sass', function () {
    return gulp
        .src('../public/src/scss/style.scss')
        .pipe(sourcemaps.init())
        .pipe(plumber(errorHandler))
        .pipe(sass({
            outputStyle: 'expanded',
            errLogToConsole: true
        }).on('error', sass.logError))
        .pipe(plumber.stop())
        .pipe(sourcemaps.write('maps'))
        .pipe(gulp.dest('../public/css'));
});

// Compile Sass Admin
gulp.task('sass-admin', function () {
    return gulp
        .src('../admin/src/scss/style-admin.scss')
        .pipe(sourcemaps.init())
        .pipe(plumber(errorHandler))
        .pipe(sass({
            outputStyle: 'expanded',
            errLogToConsole: true
        }).on('error', sass.logError))
        .pipe(plumber.stop())
        .pipe(sourcemaps.write('maps'))
        .pipe(gulp.dest('../admin/css'));
});

// Minify & Autoprefix CSS
gulp.task('css', function () {
    var processors = [
        pixrem(),
        autoprefixer({
            overrideBrowserslist: ['last 4 versions']
        }),
        cssnano()
    ];
    return gulp
        .src('../public/css/style.css')
        .pipe(postcss(processors))
        .pipe(rename('style.min.css'))
        .pipe(gulp.dest('../public/css'));
});

// Minify & Autoprefix CSS Admin
gulp.task('css-admin', function () {
    var processors = [
        pixrem(),
        autoprefixer({
            overrideBrowserslist: ['last 4 versions']
        }),
        cssnano()
    ];
    return gulp
        .src('../admin/css/style-admin.css')
        .pipe(postcss(processors))
        .pipe(rename('style-admin.min.css'))
        .pipe(gulp.dest('../admin/css'));
});

// Watch Files For Changes
gulp.task('watch', function () {
    gulp.watch('../admin/src/js/**.js', gulp.series('lint', 'scripts-admin', 'scripts-vendor-admin'));
    gulp.watch('../public/src/js/**.js', gulp.series('lint', 'scripts', 'scripts-vendor'));
    gulp.watch('../admin/src/scss/**/*.scss', gulp.series('sass-admin'));
    gulp.watch('../public/src/scss/**/*.scss', gulp.series('sass'));
});

// Default Tasks
gulp.task('default', gulp.series('sass', 'sass-admin', 'scripts', 'scripts-vendor', 'scripts-admin', 'scripts-vendor-admin', 'watch'));

// Build Tasks
gulp.task('build', gulp.series('sass', 'sass-admin', 'css', 'css-admin', 'lint', 'scripts', 'scripts-vendor', 'scripts-admin', 'scripts-vendor-admin'));
