import gulp from 'gulp';
import watch from 'gulp-watch';
import less from 'gulp-less';
import LessAutoprefix from 'less-plugin-autoprefix';
import composer from 'gulp-uglify/composer.js';
import uglifyes from 'uglify-es';
import cleanCSS from 'gulp-clean-css';
import htmlmin from 'gulp-htmlmin';
import { deleteAsync } from 'del';
import babel from 'gulp-babel';
import svgSprite from 'gulp-svg-sprites';
import cheerio from 'gulp-cheerio';
import replace from 'gulp-replace';
import fileinclude from 'gulp-file-include';
import include from 'gulp-include';
import webp from 'gulp-webp';
import px2rem from 'gulp-px2rem';
import fs from 'fs';
import path from 'path';

const autoprefix = new LessAutoprefix({ browsers: ['last 3 versions'] });
const uglify = composer(uglifyes, console);

const originalSrc = gulp.src;

gulp.src = function (...args) {
	const opts = args[1] || {};
	opts.encoding = false;
	return originalSrc.call(gulp, args[0], opts);
};

const paths = {
	src: {
		root: ['theme/*.php', 'theme/screenshot.png', 'theme/style.css', 'theme/theme.json', 'README.md'],
		templates: 'theme/template-parts/**/*.*',
		functions: 'theme/inc/**/*.*',
		woocommerce: 'theme/woocommerce/**/*.*',
		js: 'theme/js/scripts.js',
		jsFiles: 'theme/js/**/*.js',
		less: ['theme/less/styles.less', 'theme/less/admin.less', 'theme/less/editor.less'],
		img: 'theme/img/**/*.*',
		webp: ['theme/img/**/*.jpg', 'theme/img/**/*.png'],
		fonts: 'theme/fonts/**/*.*',
		css: 'theme/css/**/*.css',
		plugins: ['plugins/**/*.*', '!plugins/**/node_modules/**'],
		languages: 'theme/languages/**/*.*',
	},
	dest: {
		root: '../',
		templates: '../template-parts/',
		functions: '../inc/',
		woocommerce: '../woocommerce/',
		js: '../js/',
		less: '../css/',
		img: '../img/',
		fonts: '../fonts/',
		css: '../css/',
		plugins: '../plugins/',
		languages: '../languages/',
	},
};

function handleError(error) {
	console.log(error.toString());
	this.emit('end');
}

gulp.task('clean', async () => {
	return await deleteAsync(['../*', '!../src'], { force: true });
});

gulp.task('svgsprite', () =>
	gulp
		.src('theme/img/sprite/*.svg')
		.pipe(
			cheerio({
				run: $ => {
					$('[fill]').removeAttr('fill');
					$('[style]').removeAttr('style');
					$('clippaths, defs').remove();
					$('[clip-paths]').removeAttr('clip-paths');
					$('paths[id]').removeAttr('id');
				},
				parserOptions: { xmlMode: true },
			}),
		)
		.pipe(replace('&gt;', '>'))
		.pipe(
			svgSprite({
				mode: 'symbols',
				selector: 'icon-%f',
				svg: {
					symbols: 'sprite.php',
				},
				preview: false,
			}),
		)
		.pipe(gulp.dest('theme/template-parts/')),
);

const copyKeys = ['root', 'templates', 'plugins', 'functions', 'woocommerce', 'languages', 'css', 'img', 'fonts'];

copyKeys.forEach(key => {
	gulp.task(key, () => gulp.src(paths.src[key], { allowEmpty: true }).pipe(gulp.dest(paths.dest[key])));
});

gulp.task('less', () =>
	gulp
		.src(paths.src.less)
		.pipe(less({ plugins: [autoprefix] }))
		.on('error', handleError)
		.pipe(px2rem({ unitPrecision: 3, minPx: 1 }))
		.pipe(gulp.dest(paths.dest.less)),
);

gulp.task('less:min', () =>
	gulp
		.src(paths.src.less)
		.pipe(less({ plugins: [autoprefix] }))
		.on('error', handleError)
		.pipe(px2rem({ unitPrecision: 3, minPx: 1, replace: true }))
		.pipe(cleanCSS())
		.pipe(gulp.dest(paths.dest.less)),
);

gulp.task('less:build', () =>
	gulp
		.src(paths.src.less)
		.pipe(less({ plugins: [autoprefix] }))
		.on('error', handleError)
		.pipe(px2rem({ unitPrecision: 3, minPx: 1, replace: true }))
		.pipe(gulp.dest(paths.dest.less)),
);

gulp.task('js', () =>
	gulp
		.src(paths.src.js)
		.pipe(babel({ presets: ['@babel/env'] }))
		.on('error', handleError)
		.pipe(include())
		.pipe(gulp.dest(paths.dest.js)),
);

gulp.task('js:min', () =>
	gulp
		.src(paths.src.js)
		.pipe(babel({ presets: ['@babel/env'] }))
		.on('error', handleError)
		.pipe(include())
		.pipe(uglify({ mangle: false, ecma: 6 }))
		.pipe(gulp.dest(paths.dest.js)),
);

gulp.task('webp', () =>
	gulp
		.src(paths.src.webp)
		.pipe(webp({ quality: 75 }))
		.pipe(gulp.dest(paths.dest.img)),
);

gulp.task('js:files', () => {
	return gulp.src(paths.src.jsFiles).pipe(gulp.dest(paths.dest.js));
});

gulp.task('watch', function () {
	const webpWatcher = gulp.watch(paths.src.webp);
	webpWatcher.on('add', function (paths, stats) {
		onceWebp(paths);
	});
	webpWatcher.on('change', function (paths, stats) {
		onceWebp(paths);
	});
	function onceWebp(filename) {
		return gulp
			.src(filename, { base: 'theme/img/' })
			.pipe(
				webp({
					quality: 75,
				}),
			)
			.pipe(gulp.dest(paths.dest.img));
	}

	gulp.watch(paths.src.root, gulp.series('root'));
	gulp.watch(paths.src.templates, gulp.series('templates'));
	gulp.watch(paths.src.plugins, gulp.series('plugins'));
	gulp.watch(paths.src.css, gulp.series('css'));
	gulp.watch('theme/less/*.*', gulp.series('less'));
	gulp.watch('theme/js/**/*.js', gulp.series('js'));
	gulp.watch(paths.src.img, gulp.series('img'));
	gulp.watch('theme/img/sprite/', gulp.series('svgsprite', 'templates'));
	gulp.watch(paths.src.fonts, gulp.series('fonts'));
	gulp.watch(paths.src.functions, gulp.series('functions'));
	// gulp.watch(paths.src.woocommerce, gulp.series('woocommerce'));
	gulp.watch(paths.src.languages, gulp.series('languages'));
	gulp.watch(paths.src.jsFiles, gulp.series('js:files', 'js'));
});

gulp.task(
	'default',
	gulp.series(
		'clean',
		'root',
		'less',
		'js:files',
		'js',
		'img',
		'webp',
		'fonts',
		'svgsprite',
		'templates',
		// 'woocommerce',
		'plugins',
		'functions',
		'languages',
		'css',
		'watch',
	),
);
gulp.task(
	'min',
	gulp.series(
		'clean',
		'root',
		'less:min',
		'js:files',
		'js:min',
		'img',
		'webp',
		'fonts',
		'svgsprite',
		'templates',
		// 'woocommerce',
		'plugins',
		'functions',
		'languages',
		'css',
	),
);
gulp.task(
	'build',
	gulp.series(
		'clean',
		'root',
		'less:build',
		'js:files',
		'js',
		'img',
		'webp',
		'fonts',
		'svgsprite',
		'templates',
		// 'woocommerce',
		'plugins',
		'functions',
		'languages',
		'css',
	),
);
