const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sass = require('gulp-sass')(require('sass'));
const groupmq = require('gulp-group-css-media-queries');
const sassLint = require('gulp-sass-lint');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
var del = require('del');

var paths = {
    styles: {
        src: './sass/*.scss',
        dest: './'
    },
    scripts: {
      src: './js/*.js',
      dest: './'
    }
};

function clean() {
    return del(['assets']);
}

function styles() {
    return gulp.src(paths.styles.src)
        .pipe(plumber()) // Prevent termination on error
        .pipe(sassLint())
        .pipe(sassLint.format())
        .pipe(sassLint.failOnError())
        .pipe(sass({
            indentType: 'tab',
            indentWidth: 1,
            outputStyle: 'expanded', // Expanded so that our CSS is readable
        })).on('error', sass.logError)
        .pipe(groupmq()) // Group media queries!
        .pipe(gulp.dest(paths.styles.dest)) // Output compiled files in the same dir as Sass sources
        .pipe(bs.stream());
}

function scripts() {
    return gulp.src(paths.scripts.src, { sourcemaps: true })
      .pipe(babel())
      .pipe(uglify())
      .pipe(concat('main.min.js'))
      .pipe(gulp.dest(paths.scripts.dest));
  }

function watch() {
    gulp.watch(paths.scripts.src, scripts);
    gulp.watch(paths.styles.src, styles);
}

var build = gulp.series(clean, gulp.parallel(styles, scripts));

exports.clean = clean;
exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
exports.build = build;

exports.default = watch;