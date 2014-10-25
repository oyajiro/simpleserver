var request = require('request');
var md5 = require('MD5');
var fields = [
    'level',
    'coins',
    'last_visit',
    'visit_days',
    'continious_visit_days',
    'objects',
];
console.time('sum');
for ($i=0; $i<10000; $i++) {
    console.time($i);
    request.post('http://localhost', {
    form: {
        action: 'get',
        person_id: md5(Math.floor(Math.random() * 1000)),
        network_key: '123',
        auth_key: '123',
        fields: JSON.stringify(fields),
      }
    }, function(error, response, body) {
        console.log(body);
        console.timeEnd('sum');
    });
    console.timeEnd($i);
}
