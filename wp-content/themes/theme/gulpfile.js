import gulp from 'gulp';
import dartSass from 'sass';
import gulpSass from 'gulp-dart-sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import sourcemaps from 'gulp-sourcemaps';
import browserSyncLib from 'browser-sync';
import babel from 'gulp-babel';
import { deleteAsync } from 'del';

// Создаем экземпляр BrowserSync
const browserSync = browserSyncLib.create();

// Пути
const paths = {
    styles: {
        src: 'src/scss/style.scss',
        dest: 'dist/css/'
    },
    scripts: {
        src: ['node_modules/axios/dist/axios.min.js', 'src/js/**/*.js'],
        dest: 'dist/js/'
    },
    html: {
        src: '**/*.php'
    }
};

// Очистка папок перед сборкой
function clean() {
    return deleteAsync(['dist/css/*', 'dist/js/*']);
}

// Компиляция SCSS в CSS (Dev)
function styles() {
    return gulp.src(paths.styles.src)
        .pipe(sourcemaps.init())
        .pipe(gulpSass(dartSass, { includePaths: ['src/scss/'] }).on('error', gulpSass.logError))
        .pipe(postcss([autoprefixer()]))
        .pipe(concat('style.min.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.stream());
}

// Компиляция SCSS в CSS (Prod)
function buildStyles() {
    return gulp.src(paths.styles.src)
        .pipe(gulpSass(dartSass, { includePaths: ['src/scss/'] }).on('error', gulpSass.logError))
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(concat('style.min.css'))
        .pipe(gulp.dest(paths.styles.dest));
}

// Объединение, Babel и минификация JS (Dev)
function scripts() {
    return gulp.src(paths.scripts.src)
        .pipe(sourcemaps.init())
        .pipe(babel({ presets: ['@babel/preset-env'] })) // Поддержка старых браузеров
        .pipe(concat('main.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.scripts.dest))
        .pipe(browserSync.stream());
}

// Объединение, Babel и минификация JS (Prod)
function buildScripts() {
    return gulp.src(paths.scripts.src)
        .pipe(babel({ presets: ['@babel/preset-env'] }))
        .pipe(concat('main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.scripts.dest));
}

// Локальный сервер и слежение за файлами
function serve() {
    browserSync.init({
        proxy: "http://templatewp/",
        notify: false
    });

    gulp.watch('src/scss/**/*.scss', styles);
    gulp.watch('src/js/**/*.js', scripts);
    gulp.watch(paths.html.src).on('change', browserSync.reload);
}

// Экспорт задач
export { clean, styles, scripts };
export const watch = gulp.series(clean, gulp.parallel(styles, scripts), serve);
export default gulp.series(clean, gulp.parallel(styles, scripts), serve);
export const build = gulp.series(clean, gulp.parallel(buildStyles, buildScripts));
