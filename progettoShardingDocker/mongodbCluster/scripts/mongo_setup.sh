#!/bin/bash
sleep 10

mongosh --host mongo1:27017 <<EOF
  var cfg = {
    "_id": "myReplicaSet",
    "members": [
      {
        "_id": 0,
        "host": "mongo1"
      },
      {
        "_id": 1,
        "host": "mongo2"
      },
      {
        "_id": 2,
        "host": "mongo3"
      }
    ]
  };
  rs.initiate(cfg);
  rs.status();
EOF