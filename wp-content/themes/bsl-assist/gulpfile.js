const gulp = require('gulp');

const env = require('dotenv');
const browserSync = require('browser-sync').create();
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
const webpack = require('webpack-stream');
const clean = require('gulp-clean');
const named = require('vinyl-named');

const actions = {
    /**
     * Compile Styles and minified.
     */
    compileStyles: {
        coreStyles: () => {
            return gulp.src(['./core/assets/styles/*.scss', './app/assets/styles/*.scss'])
                .pipe(sourcemaps.init())
                .pipe(sass({ outputStyle: 'compressed', includePaths: ['node_modules', 'core/assets/styles', 'app/assets/styles'] }).on('error', sass.logError))
                .pipe(sourcemaps.write('.'))
                .pipe(gulp.dest('./public/styles'))
                .pipe(browserSync.reload({ stream: true }));
        },

        blockStyles: () => {
            return gulp.src('./app/blocks/**/*.scss')
                .pipe(sourcemaps.init())
                .pipe(sass({ outputStyle: 'compressed', includePaths: ['node_modules', 'core/assets/styles', 'app/assets/styles'] }).on('error', sass.logError))
                .pipe(sourcemaps.write('.'))
                .pipe(gulp.dest('./public/styles/blocks'))
                .pipe(browserSync.reload({ stream: true }));
        },

        componentStyles: () => {
            return gulp.src('./app/components/**/*.scss')
                .pipe(sourcemaps.init())
                .pipe(sass({ outputStyle: 'compressed', includePaths: ['node_modules', 'core/assets/styles', 'app/assets/styles'] }).on('error', sass.logError))
                .pipe(sourcemaps.write('.'))
                .pipe(gulp.dest('./public/styles/components'))
                .pipe(browserSync.reload({ stream: true }));
        }
    },

    /**
     * Compile Scripts and minified.
     */
    compileScripts: {
        coreScripts: () => {
            return gulp.src('./core/assets/scripts/*.js')
                .pipe(named())
                .pipe(webpack(require('./webpack.config.js')))
                .pipe(gulp.dest('./public/scripts'))
        },

        blockScripts: () => {
            return gulp.src('./app/blocks/**/*.js')
                .pipe(named())
                .pipe(webpack(require('./webpack.config.js')))
                .pipe(gulp.dest('./public/scripts/blocks'))
        },

        componentScripts: () => {
            return gulp.src('./app/components/**/*.js')
                .pipe(named())
                .pipe(webpack(require('./webpack.config.js')))
                .pipe(gulp.dest('./public/scripts/components'))
        }
    },

    /**
     * Clears the public directory before a clean build.
     */
    clearPublicDir: () => {
        return gulp.src('./public', { read: false, allowEmpty: true }).pipe(clean({ force: true }));
    },


    /**
     * Reloads the browser when using BrowserSync.
     */
    reloadBrowser: done => {
        browserSync.reload();
        done();
    },

    /**
     * Initializes BrowserSync.
     */
    serveBrowserSync: done => {
        env.config({ path: '.env.local' });

        browserSync.init({
            proxy: process.env.BROWSERSYNC_PROXY || '',
            port: process.env.BROWSERSYNC_PORT || 3000,
            open: false,
            injectChanges: true,
            watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir'],
            logLevel: 'silent',
        });

        console.log('BrowserSync initialized.', 'success');

        done();
    },

    watchFiles: done => {
        gulp.watch(['./core/assets/styles/**/*.scss', './app/assets/styles/**/*.scss'], gulp.parallel(
            actions.compileStyles.coreStyles,
            actions.compileStyles.blockStyles,
            actions.compileStyles.componentStyles
        ));
        gulp.watch('./app/blocks/**/*.scss', actions.compileStyles.blockStyles);
        gulp.watch('./app/components/**/*.scss', actions.compileStyles.componentStyles);
        gulp.watch('./core/assets/scripts/*.js', actions.compileScripts.coreScripts);
        gulp.watch('./app/blocks/**/*.js', gulp.parallel(
            actions.compileScripts.blockScripts,
            actions.compileScripts.blockScripts,
            actions.compileScripts.componentScripts
        ));
        gulp.watch('./app/components/**/*.js', actions.compileScripts.componentScripts);
        gulp.watch('./**/*.php', actions.reloadBrowser);

        console.log('Now watching files.', 'success');

        done();
    }
}

const tasks = {

    default: () => {
        const taskActions = [
            actions.clearPublicDir,
            gulp.parallel(
                actions.compileStyles.coreStyles,
                actions.compileStyles.blockStyles,
                actions.compileStyles.componentStyles,
                actions.compileScripts.coreScripts,
                actions.compileScripts.blockScripts,
                actions.compileScripts.componentScripts
            ),
            gulp.parallel(actions.serveBrowserSync, actions.watchFiles),
        ];

        return gulp.series(taskActions);
    }
};

exports.default = tasks.default();