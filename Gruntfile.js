module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            js: {
                src: [
                    'js/front/jquery.jcarousel.js',
                    'js/front/jquery.dotdotdot.js',
                    'js/front/jquery.dcjqaccordion.2.7.js',
                    'js/front/jquery.cookie.js',
                    'js/front/easyTooltip.js',
                    'js/front/jquery.hoverIntent.minified.js',
                    'js/front/jquery.mCustomScrollbar.concat.min.js',
                    'js/front/jquery.carouFredSel.min.js',
                    'js/front/frontend.js',
                    'js/front/search.js',
                    'js/front/cart.js',
                    'js/alertify.js'
                ],
                dest: 'distribution/js/scripts.js',
            },
            css: {
                src: [
                    'css/front/frontend.css',
                    'css/front/accordion.css',
                    'css/front/jquery.mCustomScrollbar.css',
                    'css/front/tip-darkgray/tip-darkgray.css',
                    'css/ui/jquery-ui-1.10.3-min.css',
                    'css/alertify/core.css',
                    'css/alertify/default.css'
                ],
                dest: 'distribution/css/styles.css',
            },
        },
        uglify: {
            options: {
                stripBanners: true,
                banner: '/* <%= pkg.name %> - v<%= pkg.version %> */\n'
            },
            js: {
                files: {
                    'distribution/js/scripts.min.js': ['distribution/js/scripts.js']
                }
            }
        },
        cssmin: {
            target: {
                files: {
                    'distribution/css/styles.min.css': ['distribution/css/styles.css']
                }
            }
        },
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.registerTask('default', ['concat', 'uglify', 'cssmin']);
};