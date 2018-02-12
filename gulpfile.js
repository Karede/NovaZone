var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var cleanCSS = require('gulp-clean-css');

gulp.task('sass', function () {
    gulp.src('scss/*.scss')
        .pipe(sass())
        .pipe(rename(rename({
            suffix: '.min'
        })))
        .pipe(cleanCSS())
        .pipe(gulp.dest('./css/'))
});

gulp.task('imagemin', function () {
    return gulp.src('images/*')
        .pipe(imagemin({
            progressive: true
        }))
        .pipe(gulp.dest('imagemin-img'));
});

gulp.task('default', ['sass'], function () {
    gulp.watch('scss/*.scss', ['sass']);
});

