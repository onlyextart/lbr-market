module.exports = function (grunt) {

    grunt.initConfig({
        concat: {
            options: {
                separator: ';\n',
            },
            js: {
                src: ['js/front/search.js', 'js/front/cart.js'],
                dest: 'distributin/js/scripts.js',
            },
            css: {
                src: [],
                dest: 'distributin/css/styles.js',
            },
        },
        watch: {
            js: {
                files: ['js/front/**/*.js'],
                tasks: ['concat:js'],
                options: {
                    spawn: false,
                },
            },
            css: {
                files: ['css/front/**/*.css'],
                tasks: ['concat:css'],
                options: {
                    spawn: false,
                },
            },
        },
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
};