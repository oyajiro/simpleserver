var request = require('request');
var fields = [
    'level',
    'coins',
    'objects',
]
request.post('http://localhost', {
  form: {
    action: 'get',
    person_id: '0290348ad800cf400d36ec00b96c78bb',
    network_key: '123',
    auth_key: '123',
    fields: JSON.stringify(fields),
  }
}, function(error, response, body) {
  console.log(body);
});