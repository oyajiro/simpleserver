var request = require('request');
var md5 = require('MD5');
var fields = [
    'level',
    'coins',
    'objects',
];
console.time('sum');
for ($i=0; $i<10000; $i++) {
    console.log($i);
    console.time($i);
    var data = {
        level: Math.floor(Math.random() * 100),
        coins: Math.floor(Math.random() * 100),
        last_visit: console.time(),
        continious_visit_days: Math.floor(Math.random() * 80),
        objects: [
            {
                name: md5(Math.floor(Math.random() * 100000)),
                data: 'some_text',
            },
            {
                name: md5(Math.floor(Math.random() * 100000)),
                data: 'some_text_longer',
            },
            {
                name: md5(Math.floor(Math.random() * 100000)),
                data: 'some_text',
            },
        ],
    };
    request.post('http://localhost', {
    form: {
        action: 'save',
        person_id: md5(Math.floor(Math.random() * 1000)),
        network_key: '123',
        auth_key: '123',
        data: JSON.stringify(data),
        // fields: JSON.stringify(fields),
      }
    }, function(error, response, body) {
        console.log(body);
        console.timeEnd('sum');
    });
    console.timeEnd($i);
}
console.timeEnd('sum');
