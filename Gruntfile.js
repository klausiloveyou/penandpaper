module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> - <%= pkg.version %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                mangle: false
            },
            main: {
                options: {
                    sourceMap: true
                },
                files: [{
                    expand: true,
                    cwd: 'src/dist/js',
                    src: '*.js',
                    dest: 'src/dist/js',
                    ext: '.min.js'
                }]
            }
        },
        cssmin: {
            main: {
                options: {
                    sourceMap: true
                },
                files: [{
                    expand: true,
                    cwd: 'src/dist/css',
                    src: ['*.css', '!*.min.css'],
                    dest: 'src/dist/css',
                    ext: '.min.css'
                }]
            }
        },
        copy: {
            main: {
                expand: true,
                cwd: 'src',
                src: ['**', '!config/mongodb_dev.php'],
                dest: 'build/'
            }
        },
        clean: ['build']
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    // Default task(s).
    grunt.registerTask('default', ['uglify', 'cssmin', 'clean', 'copy']);
};