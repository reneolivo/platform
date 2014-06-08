'use strict';
module.exports = function(grunt) {

    grunt.initConfig({
        recess: {
            dist: {
                options: {
                    compile: true,
                    compress: true
                },
                files: {
                    'css/app.min.css': [
                        'vendor/css/bootstrap-midnight.css',
                        'vendor/css/bootstrap-theme-midnight.css',
                        'vendor/css/font-awesome.min.css',
                        'vendor/css/bootstrap-colorpicker.min.css',
                        'vendor/css/bootstrap-datepicker.css',
                        'vendor/css/bootstrap-wysihtml5.css',
                        'vendor/css/select2.css',
                        'vendor/css/select2-bootstrap.css',
                        'vendor/css/jquery.dataTables.bootstrap.css',
                        'vendor/css/bootstrap-social.css',
                        'vendor/css/timeline.css',
                        'css/sprites.css',
                        'less/app.less',
                        'vendor/css/helpers.css',
                    ]
                }
            }
        },
        jshint: {
            options: {
                jshintrc: '.jshintrc'
            },
            files: [
                'Gruntfile.js',
                'js/*.js'
            ]
        },
        jsbeautifier: {
            files: ['Gruntfile.js', 'js/*.js']
        },
        uglify: {
            dist: {
                files: {
                    'js/app.min.js': [
                        'vendor/js/jquery.min.js',
                        'vendor/js/bootstrap.min.js',
                        'vendor/js/handlebars.js',
                        'vendor/js/jquery.metisMenu.js',
                        'vendor/js/jquery-ui.custom.js',
                        'vendor/js/bootstrap-colorpicker.min.js',
                        'vendor/js/bootstrap-datepicker.js',
                        'vendor/js/bootstrap-wysihtml5.min.js',
                        'vendor/js/select2.min.js',
                        'vendor/js/jquery.dataTables.min.js',
                        'vendor/js/jquery.dataTables.bootstrap.js',
                        'vendor/js/dropzone.js',
                        'js/app.js'
                    ]
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            less: {
                files: [
                    'less/*.less',
                    'css/*.css',
                    '!css/app.min.css'
                ],
                tasks: ['recess']
            },
            js: {
                files: [
                    'js/*.js',
                    '!js/app.min.js'
                ],
                tasks: ['jsbeautifier', 'uglify']
            }
        },
        clean: {
            dist: [
                'css/app.min.css',
                'js/app.min.js'
            ]
        }
    });

    // Load tasks
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-recess');
    grunt.loadNpmTasks('grunt-jsbeautifier');

    // Register tasks
    grunt.registerTask('default', [
        'clean',
        'recess',
        'jsbeautifier',
        'uglify'
    ]);
    grunt.registerTask('dev', [
        'watch'
    ]);

};
