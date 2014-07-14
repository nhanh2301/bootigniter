module.exports = function(grunt) {
  'use strict';

  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),

    banner: '/*!\n' +
            ' * BootIgniter v<%= pkg.version %> (<%= pkg.homepage %>)\n' +
            ' * Copyright -<%= grunt.template.today("yyyy") %> <%= pkg.author %>\n' +
            ' * Licensed under <%= pkg.license.type %> (<%= pkg.license.url %>)\n' +
            ' */\n',

    clean: {
      frontstyle: 'asset/css/<%= pkg.name %>.*',
      frontscript: 'asset/js/<%= pkg.name %>.*'
    },

    php: {
      watch: {
        options: {
          port: 8086,
          open: true
        }
      },
      server: {
        options: {
          keepalive: true,
          port: 8086
        }
      }
    },

    phplint: {
      backend: [
        'application/bootigniter/helpers/**/*.php',
        'application/bootigniter/libraries/**/*.php',
        'application/controllers/**/*.php',
        'application/core/*.php',
        'application/hooks/**/*.php',
        'application/helpers/**/*.php',
        'application/libraries/**/*.php',
        'application/models/**/*.php',
        'application/views/**/*.php'
      ],
      test: [
        '<%= phpunit.backend %>/**/*.php'
      ]
    },

    phpunit: {
      options: {
        bin: 'vendor/bin/phpunit'
      },
      backend: {
        dir: 'application/tests/backend'
      }
    },

    less: {
      options: {
        strictMath: true,
        outputSourceFiles: true
      },
      frontstyle: {
        files: {
          "asset/css/<%= pkg.name %>.css": "asset/less/style.less",
        }
      }
    },

    autoprefixer: {
      options: {
        browsers: [
          'Android 2.3',
          'Android >= 4',
          'Chrome >= 20',
          'Firefox >= 24', // Firefox 24 is the latest ESR
          'Explorer >= 8',
          'iOS >= 6',
          'Opera >= 12',
          'Safari >= 6'
        ],
        map: true
      },
      frontstyle: {
        src: 'asset/css/<%= pkg.name %>.css',
        dest: 'asset/css/<%= pkg.name %>.css'
      }
    },

    csscomb: {
      options: {
        config: 'asset/less/.csscomb.json'
      },
      frontstyle: {
        expand: true,
        cwd: 'asset/css/',
        dest: 'asset/css/',
        src: '<%= pkg.name %>.css'
      }
    },

    csslint: {
      options: grunt.file.readJSON('asset/less/.csslintrc'),
      frontstyle: '<%= autoprefixer.frontstyle.dest %>'
    },

    cssmin: {
      frontstyle: {
        expand: true,
        report: 'gzip',
        cwd: 'asset/css/',
        src: '<%= pkg.name %>.css',
        dest: 'asset/css/',
        ext: '.min.css'
      }
    },

    jshint: {
      options: {
        jshintrc: 'asset/js/.jshintrc'
      },
      frontscript: {
        src: [
          'asset/js/script.js',
        ]
      }
    },

    jscs: {
      options: {
        config: 'asset/js/.jscsrc'
      },
      frontscript: {
        src: '<%= jshint.frontscript.src %>'
      }
    },

    concat: {
      options: {
        banner: '<%= banner %>',
        stripBanners: false
      },
      frontscript: {
        src: '<%= jscs.frontscript.src %>',
        dest: 'asset/js/<%= pkg.name %>.js'
      }
    },

    uglify: {
      options: {
        preserveComments: 'some'
      },
      frontscript: {
        src: '<%= concat.frontscript.dest %>',
        dest: 'asset/js/<%= pkg.name %>.min.js'
      }
    },

    usebanner: {
      options: {
        position: 'top',
        banner: '<%= banner %>'
      },
      frontstyle: {
        src: 'asset/css/*.css'
      }
    },

    watch: {
      options: {
        nospawn: true,
        livereload: true
      },
      backend: {
        files: '<%= phplint.backend %>',
        tasks: 'phptest'
      },
      frontstyle: {
        files: [
          'asset/less/**/*.less'
        ],
        tasks: [ 'cssdist', 'csstest' ]
      },
      frontscript: {
        files: '<%= jshint.frontscript.src %>',
        tasks: [ 'jshint', 'jscs' ]
      },
    }

  });

  require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});
  require('time-grunt')(grunt);

  grunt.registerTask('phptest', ['phplint', 'phpunit']);

  grunt.registerTask('cssdist', ['clean:frontstyle', 'less', 'autoprefixer', 'csscomb']);

  grunt.registerTask('csstest', ['csslint', 'cssmin', 'usebanner']);

  grunt.registerTask('jsdist',  ['clean:frontscript', 'concat', 'uglify']);

  grunt.registerTask('jstest',  ['jshint', 'jscs']);

  grunt.registerTask('build',   ['phptest', 'cssdist', 'csstest', 'jsdist', 'jstest']);

  grunt.registerTask('default', ['php:watch', 'watch']);
}
