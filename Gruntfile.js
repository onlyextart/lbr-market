module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            /*options: {
                separator: ';\n',
            },*/
            js: {
                src: [
                    /*'js/jquery.jcarousel.min.js',
                    'js/jquery.carouFredSel.min.js',
                    'js/jquery.mCustomScrollbar.concat.min.js',
                    'js/alertify.min.js',
                    'js/jquery.dotdotdot.min.js',
                    'js/jquery.dcjqaccordion.2.7.min.js',
                    'js/easyTooltip.js',
                    'js/jquery.hoverIntent.minified.js',
                    'js/jquery.cookie.min.js',
                    'js/front/frontend.js',
                    'js/front/search.js',
                    'js/front/cart.js'*/
                    
                    'js/front/jquery.jcarousel.js',
                    'js/front/alertify.js',
                    'js/front/jquery.dotdotdot.js',
                    'js/front/jquery.dcjqaccordion.2.7.js',
                    'js/front/frontend.js',
                    'js/front/search.js',
                    'js/front/cart.js',
                    'js/front/jquery.cookie.js',
                    'js/easyTooltip.js',
                    'js/jquery.hoverIntent.minified.js',
                    'js/jquery.mCustomScrollbar.concat.min.js',
                    'js/jquery.carouFredSel.min.js'
                ],
                dest: 'distribution/js/scripts.js',
            },
            css: {
                src: [
                    'css/ui/jquery-ui-1.10.3-min.css',
                    'css/front/orig/frontend.css',
                    'css/front/orig/accordion.css',
                    'css/front/orig/jquery.mCustomScrollbar.css',
                    'css/front/alertify/core.css',
                    'css/front/alertify/default.css',
                    'css/front/tip-darkgray/tip-darkgray.css'
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