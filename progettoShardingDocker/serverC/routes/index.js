var express = require('express');
const { MongoClient } = require('mongodb');
var router = express.Router();
const uri = 'mongodb://localhost:27020/?directConnection=true'; // Replace with your sharded MongoDB connection strings  
const client = new MongoClient(uri, { useNewUrlParser: true, useUnifiedTopology: true });

const cors = require('cors');

router.use(cors());


async function main(nome, contenuto, prezzo) {
  try {
    // Connect to the sharded cluster
    await client.connect();
    console.log('Connected to the sharded MongoDB cluster');

    // Example query
    const db = client.db('dbSharded'); // Replace with your actual database name
    const collection = db.collection('collectionC'); // Replace with your actual collection name

    // Insert a document
    const insertResult = await collection.insertOne({ nome: nome, contenuto: contenuto, prezzo: prezzo});
    
    console.log('Inserted document:', insertResult.insertedId);

    // Find the inserted document
    const findResult = await collection.findOne({ _id: insertResult.insertedId });
    console.log('Found document:', findResult);

    // Close the connection
    await client.close();
    console.log('Connection closed');
  } catch (err) {
    console.error('Error:', err);
  }
}

/* GET home page. */
router.get('/', function(req, res, next) {
  client.connect()
  .then(()=>{
    console.log("Connected correctly to server");
    
    const db=client.db("dbSharded");
    const collection=db.collection("collectionC");

    return collection.find({}).toArray();
  })
  .then(documents=>{
    console.log("Contuenuto della collection prodotti: ");

    console.log(documents);

    res.render('index', { title: 'Express', documents : documents});
    
  })
  .catch(err=>{
    console.log(err);
  })
  .finally(()=>{
    client.close();
  });
});

router.get('/insert', function(req, res, next) {
  var nome = req.query.nome;
  var contenuto = req.query.contenuto;
  var prezzo = req.query.prezzo;

  main(nome,contenuto,prezzo).catch(console.error);
  res.send('Insertion completed');
});

module.exports = router;
