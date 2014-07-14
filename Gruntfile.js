module.exports = function(grunt) {
  'use strict';

  grunt.initConfig({
    // Metadata.
    php: {
      dist: {
        options: {
          keepalive: true,
          open: true,
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
      ]
    },

    phpunit: {
      options: {
        bin: 'vendor/bin/phpunit'
      },
      backend: {
        dir: 'tests'
      }
    },

    watch: {
      options: {
        nospawn: true,
        livereload: true
      },
      backend: {
        files: '<%= phplint.backend %>',
        tasks: 'phpunit'
      }
    }

  });

  require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});
  require('time-grunt')(grunt);

  grunt.registerTask('phptest', ['phplint', 'phpunit']);

  grunt.registerTask('default', ['php']);
}
