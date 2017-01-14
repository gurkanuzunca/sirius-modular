module.exports = function(grunt) {
  const COMPILEPATH = grunt.option('path');

  console.log(COMPILEPATH);


  var config = require('./' + COMPILEPATH +'/compile');
  var bower = require("bower");
  var fs = require("fs");
  var path = require("path");
  var rimraf = require("rimraf");
  var files = [];

  var scriptFullpath = COMPILEPATH + '/' + config.output.path + '/' + config.output.script;
  var styleFullpath = COMPILEPATH + '/' + config.output.path + '/' + config.output.style;


  function exists(file) {
    try {
      fs.accessSync(file);
    } catch (e) {
      return false;
    }
    return true;
  }


  function copy(file, directory) {
    if (exists(file)) {
      var filename;
      var extension = path.extname(file);
      var basename = path.basename(file, extension);
      var i = 0;

      while (exists(filename = directory + basename + (i ? "-" + i : "") + extension)) {
        i++;
      }
      grunt.file.copy(file, filename);

      //fs.createReadStream(file).pipe(fs.createWriteStream(filename));
      return filename;
    } else {
      throw new Error("File could not found");
    }
  }



  // Önceki dosyalar silinir.
  rimraf.sync(COMPILEPATH + '/' + config.output.path);


  grunt.registerTask("rebase-url", function() {
    var css = fs.readFileSync(styleFullpath, "utf8").toString();


    var doneCallback = this.async();
    var output = '';

    if (css) {
      output = css.replace(/url\s*\(\s*(['"]?)([^"'\)]*)\1\s*\)/gi, function(match, location) {

        // Boşluklar, tırnaklar, ve ters slaşlar temizlenir url() ekleri de temizlenir.
        var url = match.replace(/\s/g, '').slice(4, -1).replace(/"|'/g, '').replace(/\\/g, '/');
        var dir = path.resolve(COMPILEPATH + '/' + config.pluginPath +'/', path.dirname(url));

        if (/^\/|https:|http:|data:/i.test(url) === false) {
          var filename = path.basename(url);
          var filePath = path.resolve(dir + '/' + filename.split(/[?#]/)[0]);

          if (files.indexOf(filePath) === -1) {
            files.push(filePath);
            filename = copy(filePath.replace('/', ''), COMPILEPATH + '/' + config.output.path + '/');

          }
          console.log(filename);

          return 'url("' + path.basename(filename) + '")';
        } else {
          return 'url("' + url + '")';
        }
      });

      grunt.file.write(styleFullpath, output);
      doneCallback();
    } else {
      console.log('File not found');
    }

  });



  grunt.registerTask("bower-install", function() {
    var plugin, name, installNext;
    var doneCallback = this.async();
    var backup = config.plugins.slice();

    (installNext = function() {
      plugin = backup.pop();

      if (plugin) {
        name = plugin.package + (plugin.version ? "#" + plugin.version : "");
        bower.commands.install([name], undefined, {
          directory: COMPILEPATH + '/' + config.pluginPath
        }).on("end", function() {
          installNext();
        }).on("err", function(err) {
          console.log(err);
          installNext();
        });
      } else {
        doneCallback();
      }
    })();
  });


  grunt.registerTask("bower-clear", function() {
    rimraf.sync(COMPILEPATH + '/' + config.pluginPath);
  });



  var scripts = [];
  var styles = [];

  for (var i = 0; i < config.plugins.length; i++) {
    if (config.plugins[i].scripts) {
      scripts = scripts.concat(config.plugins[i].scripts.map(x => COMPILEPATH + '/' + config.pluginPath + '/' + config.plugins[i].package + '/' + x));
    }

    if (config.plugins[i].styles) {
      styles = styles.concat(config.plugins[i].styles.map(x => COMPILEPATH + '/' + config.pluginPath + '/' + config.plugins[i].package + '/' + x));
    }
  }

  if (config.scripts) {
    scripts = scripts.concat(config.scripts.map(x => COMPILEPATH + '/' + config.path + '/' + x));
  }

  if (config.styles) {
    styles = styles.concat(config.styles.map(x => COMPILEPATH + '/' + config.path + '/' + x));
  }




  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    concat: {
      dist: {
        src: scripts,
        dest: scriptFullpath
      }
    },

    cssmin: {
      options: {
        rebase: true
      },

      compile: {
        files: [{
          src: styles,
          dest: styleFullpath
        }]
      }
    }
  });


  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  // Default task(s).
  grunt.registerTask('default', ['bower-install', 'concat', 'cssmin', 'rebase-url', 'bower-clear']);
};