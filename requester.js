var request = require('request');
var fields = [
    'level',
    'coins',
    'objects',
];
var data = {
    level: 100,
    coins: 100,
    last_visit: 1414234643,
    continious_visit_days: 101,
    objects: [
        {
            name: 'some_name',
            data: 'some_text',
        },
        {
            name: 'some_name2',
            data: 'some_text_longer',
        },
        {
            name: 'some_name3',
            data: 'some_text',
        },
    ],
};
request.post('http://localhost', {
  // form: {
  //   action: 'get',
  //   person_id: '0290348ad800cf400d36ec00b96c78bb',
  //   network_key: '123',
  //   auth_key: '123',
  //   fields: JSON.stringify(fields),
  // }
form: {
    action: 'save',
    person_id: '003c6f76523766e4493ab474addc542b',
    network_key: '123',
    auth_key: '123',
    data: JSON.stringify(data),
    // fields: JSON.stringify(fields),
  }
}, function(error, response, body) {
  console.log(body);
});