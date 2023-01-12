// const redis = require('redis');
// const publisher = redis.createClient();
//
// (async () => {
//
//     const article = {
//         id: '123456',
//         name: 'Using Redis Pub/Sub with Node.js',
//         blog: 'Logrocket Blog',
//     };
//
//     await publisher.connect();
//
//     await publisher.publish('article', JSON.stringify(article));
// })();

const redis = require('redis');

(async () => {

    const client = redis.createClient();

    const subscriber = client.duplicate();

    await subscriber.connect();

    await subscriber.subscribe('message', (message) => {
        console.log(message); // 'message'
    });

})();
