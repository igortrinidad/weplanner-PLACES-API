    
    /* ADICIONA NOVA VERSÃO AO REPO */

    let fs = require('fs');
    let fileName = '../package.json';
    let file = require(fileName);

    let oldVersion = file.version;
    let versionArray = file.version.split('.');

    let env = process.env.env;

    versionArray[2] = parseInt(versionArray[2])+1

    if(env == 'production'){
      	versionArray[1] = parseInt(versionArray[1])+1
    }

    file.version = versionArray.join(".");

    fs.writeFile(fileName, JSON.stringify(file, null, 4));

    console.log(file.version);
