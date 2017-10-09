#!/bin/bash

trap_with_arg() {
    func="$1" ; shift
    for sig ; do
        trap "$func $sig" "$sig"
    done
}

func_trap() {
    echo $1
    exit 0
}

trap_with_arg func_trap HUP INT TERM EXIT

while true; do
  sleep 1000 & wait
done

