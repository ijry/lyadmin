#!/bin/bash

terminate() {
  echo "test"
  exit 0
}

trap terminate SIGHUP SIGINT SIGTERM

while true; do
  sleep 1000 & wait
done
