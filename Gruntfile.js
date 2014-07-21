module.exports = function(grunt) {
  'use strict';

  // Force use of Unix newlines
  grunt.util.linefeed = '\n';

  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),

    // Banner
    banner: '/*!\n' +
            ' * BootIgniter v<%= pkg.version %> (<%= pkg.homepage %>)\n' +
            ' * Copyright 2014 <%= pkg.author %>\n' +
            ' * Licensed under <%= pkg.license.type %> (<%= pkg.license.url %>)\n' +
            ' */\n',

    // Cleanup build result files
    clean: {
      frontstyle: 'asset/css/<%= pkg.name %>.*',
      frontscript: 'asset/js/<%= pkg.name %>.*'
    },

    // Start PHP Build-in server
    php: {
      server: {
        options: {
          port: 8086,     // http://localhost:8086
          open: true      // Automaticaly open default webbrowser
        }
      }
    },

    // Check all *.php files from typo error
    phplint: {
      backend: [
        'application/bootigniter/**/*.php',
        'application/controllers/**/*.php',
        'application/core/*.php',
        'application/hooks/**/*.php',
        'application/helpers/**/*.php',
        'application/libraries/**/*.php',
        'application/models/**/*.php',
        'application/views/**/*.php'
      ],
      test: [
        '<%= phpunit.backend.dir %>/**/*.php'
      ]
    },

    // PHP Unit Testing
    phpunit: {
      options: {
        bin: 'vendor/bin/phpunit'
      },
      backend: {
        dir: 'tests/application'
      }
    },

    // Compile *.less files
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

    // Enable browser specific prefixes
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

    // Make sure everything is in same coding standard
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

    // Check all *.css files from typo error
    csslint: {
      options: grunt.file.readJSON('asset/less/.csslintrc'),
      frontstyle: '<%= autoprefixer.frontstyle.dest %>'
    },

    // Minify all *.css files
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

    // Add banner on top of all *.css files
    usebanner: {
      options: {
        position: 'top',
        banner: '<%= banner %>'
      },
      frontstyle: {
        src: 'asset/css/*.css'
      }
    },

    // Check all *.js files from typo error
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

    // Make sure everything is in same coding standard
    jscs: {
      options: {
        config: 'asset/js/.jscsrc'
      },
      frontscript: {
        src: '<%= jshint.frontscript.src %>'
      }
    },

    // Combine all *.js files into one single file
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

    // Minify combined *.js file
    uglify: {
      options: {
        preserveComments: 'some'
      },
      frontscript: {
        src: '<%= concat.frontscript.dest %>',
        dest: 'asset/js/<%= pkg.name %>.min.js'
      }
    },

    // Watch file changes and run task base on file ext
    watch: {
      options: {
        nospawn: true,
        livereload: true
      },
      // Run `phptest` only for *.php files
      backend: {
        files: '<%= phplint.backend %>',
        tasks: 'phptest'
      },
      // Run `cssdist` & `csstest` only for *.css files
      frontstyle: {
        files: [
          'asset/less/**/*.less'
        ],
        tasks: [ 'cssdist', 'csstest' ]
      },
      // Run `jshint` & `jscs` only for *.js files
      frontscript: {
        files: '<%= jshint.frontscript.src %>',
        tasks: [ 'jshint', 'jscs' ]
      },
    }

  });

  // Load all grunt development dependencies
  require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});
  require('time-grunt')(grunt);

  // Alias for 'phplint' and 'phpunit' task
  grunt.registerTask('phptest', ['phplint', 'phpunit']);

  // Alias for 'clean:frontstyle', 'less', 'autoprefixer' and 'csscomb' task
  grunt.registerTask('cssdist', ['clean:frontstyle', 'less', 'autoprefixer', 'csscomb']);

  // Alias for 'csslint', 'cssmin' and 'usebanner' task
  grunt.registerTask('csstest', ['csslint', 'cssmin', 'usebanner']);

  // Alias for 'clean:frontscript', 'concat' and 'uglify' task
  grunt.registerTask('jsdist',  ['clean:frontscript', 'concat', 'uglify']);

  // Alias for 'jshint' and 'jscs' task
  grunt.registerTask('jstest',  ['jshint', 'jscs']);

  // Alias for 'phptest', 'cssdist', 'csstest', 'jsdist' and 'jstest' task
  grunt.registerTask('build',   ['phptest', 'cssdist', 'csstest', 'jsdist', 'jstest']);

  // Alias for 'php:server' and 'watch' task
  grunt.registerTask('default', ['php:server', 'watch']);
}
