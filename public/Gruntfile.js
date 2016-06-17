module.exports = function(grunt) {

    // 1. Вся настройка находится здесь
    grunt.initConfig({
        pkg: grunt.file.readJSON('../package.json'),

        scsslint: {
            allFiles: [
                'css/dev/scss/*.scss',
                'css/dev/scss/base/*.scss',
                'css/dev/scss/layout/*.scss',
                'css/dev/scss/modules/*.scss',
                'css/dev/scss/utils/*.scss'
            ],
            options: {
                //bundleExec: true,
                config: '.scss-lint.yml',
                reporterOutput: 'scss-lint-report.xml',
                colorizeOutput: true
                //exclude: 'css/dev/scss/modules/_simform.scss',
            }
        },

        concat: {
            dist: {
                src: [
                    'vendor/js/*.js',
                    'js/index.js'
                ],
                dest: 'js/build/common.js'
            }
        },

        uglify: {
            build: {
                src: 'js/build/common.js',
                dest: 'js/build/common.min.js'
            }
        },

        imagemin: {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: 'media/img/',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'media/img_min/'
                }]
            }
        },

        compass: {
            dist: {
                options: {
                    config: 'config.rb'
                    //require: ['susy', 'breakpoint']
                }
            }
        },

        csscomb: {
            dist: {
                options: {
                    config: '.csscomb.json'
                },
                files: {
                    'css/build/styles.css': ['css/dev/styles.css']
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['last 2 versions', 'ie 8', 'ie 9', '> 1%']
            },
            main: {
                expand: true,
                flatten: true,
                src: 'css/dev/*.css',
                dest: 'css/build/'
            }
        },

        postcss: {
            options: {
                map: {
                    inline: false, // save all sourcemaps as separate files...
                    annotation: 'css/dev' // ...to the specified directory
                },

                processors: [
                    require('pixrem')() // add fallbacks for rem units
                ]
            },
            dist: {
                src: 'css/build/*.css'
            }
        },

        cssnano: {
            options: {
                sourcemap: true,
                calc: false,
                zindex: false
            },
            dist: {
                files: {
                    'css/build/styles.min.css': 'css/build/styles.css'
                }
            }
        },

        clean: {
            build: ["css/dev/styles.css", "css/dev/ie-styles.css"]
        },

        watch: {
            options: {
                dateFormat: function(time) {
                    grunt.log.writeln('The watch finished in ' + time + 'ms at' + (new Date()).toString());
                    grunt.log.writeln('Waiting for more changes...');
                },
                livereload: true
            },
            scripts: {
                files: ['js/*.js'],
                tasks: ['concat', 'uglify'],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
            scss: {
                files: ['css/dev/scss/*.scss', 'css/dev/scss/base/*.scss',
                    'css/dev/scss/layout/*.scss', 'css/dev/scss/modules/*.scss'],
                tasks: ['compass', 'csscomb', 'autoprefixer', 'postcss', 'cssnano'],
                options: {
                    spawn: false
                }
            },
            html:{
                files: ['./**/*.html'],
                tasks: [],
                options: {
                    spawn: false,
                    livereload: true
                }
            },
            php:{
                files: ['./**/*.php'],
                tasks: [],
                options: {
                    spawn: false,
                    livereload: true
                }
            }
        }

    });

    // 3. Тут мы указываем Grunt, что хотим использовать этот плагин
    //grunt.loadNpmTasks('grunt-scss-lint');                  // https://www.npmjs.com/package/grunt-scss-lint
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-csscomb');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-cssnano');
    //grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // 4. Указываем, какие задачи выполняются, когда мы вводим «grunt» в терминале
    grunt.registerTask('default', ['concat', 'uglify', 'compass', 'csscomb', 'autoprefixer', 'postcss', 'cssnano']);
    //grunt.registerTask('default', ['concat', 'uglify', 'imagemin', 'compass', 'csscomb', 'autoprefixer', 'postcss']);

};