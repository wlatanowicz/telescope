var gulp = require('gulp');
var requireDir = require('require-dir');

requireDir('./compiler/gulp_tasks');

gulp.task('build', ['tomek-build'] );

gulp.task( 'watch', function () {
    gulp.watch( [ './app/**/*' ], [ 'tomek-build' ] );
} );

gulp.task('build-unit-tests', function () {
    var exec = require( 'child_process' ).execSync;
    exec('./node_modules/.bin/tsc --project ./test/tsconfig.json', {stdio:[0,1,2]});
});

gulp.task('unit-test', ['build-unit-tests'], function () {
    var exec = require( 'child_process' ).execSync;
    exec('./node_modules/.bin/alsatian "./test/unit/**/*.spec.js"', {stdio:[0,1,2]});
});
