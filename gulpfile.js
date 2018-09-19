var gulp = require('gulp');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify');

gulp.task('default', function () {
    let path = "src/resources/assets/laravel-admin/";
    // 将你的默认的任务代码放在这
    gulp.src(path + "!(*.min.js)")
        .pipe(minify({
            ext: {
                min: ".min.js"
            }
        }))
        .pipe(gulp.dest(path));
});


