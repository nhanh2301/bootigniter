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
        'application/core/*.php',
        'application/controllers/**/*.php',
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

  grunt.registerTask('default', ['php']);
}
