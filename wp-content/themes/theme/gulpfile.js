const gulp = require('gulp');
const dartSass = require('sass');
const gulpSass = require('gulp-dart-sass');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const rename = require('gulp-rename');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync').create();

// Пути к файлам
const paths = {
    styles: {
        src: 'src/scss/style.scss',
        dest: 'dist/css/'
    },
    scripts: {
        src: 'src/js/**/*.js',
        dest: 'dist/js/'
    },
    html: {
        src: '**/*.php'
    }
};

// Очистка папок перед сборкой
async function clean() {
    const { deleteAsync } = await import('del');
    return deleteAsync(['dist/css/*', 'dist/js/*']);
}

// Компиляция SCSS → CSS
function styles() {
    return gulp.src(paths.styles.src)
        .pipe(sourcemaps.init())
        .pipe(gulpSass(dartSass).on('error', gulpSass.logError))
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(rename({ suffix: '.min' }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.stream());
}


// Объединение и минификация JS
function scripts() {
    return gulp.src(paths.scripts.src)
        .pipe(sourcemaps.init())
        .pipe(concat('main.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.scripts.dest))
        .pipe(browserSync.stream());
}

// Локальный сервер и слежение за изменениями
function serve() {
    browserSync.init({
        proxy: "http://templatewp/",
        notify: false
    });

    gulp.watch('src/scss/**/*.scss', styles);
    gulp.watch('src/js/**/*.js', scripts);
}

// Задачи Gulp
exports.clean = clean;
exports.styles = styles;
exports.scripts = scripts;
exports.watch = gulp.series(clean, styles, scripts, serve);
exports.default = gulp.series(clean, styles, scripts, serve);

