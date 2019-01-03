#!/bin/bash

# Autor:	da_fuchs
# Version:	0.1
# Desc:		Shell tool to handle P&P developement environment
# Usage: 	sh dev.sh [-v] command

# Handle options
while getopts ":v" opt; do
	case $opt in
		v)
			echo "dev version 0.1" >&2
			exit 1
			;;
		\?)
			echo "Invalid option: -$OPTARG" >&2
			exit 1
			;;
	esac
done

# Check commands
if [ "$#" -ne 1 ]
	then
		echo "Usage: dev.sh [-v] command (i.e. sh dev.sh build)"
		exit 1
fi

# Execute commands
case $1 in
    build)
        echo "Building DEV environment..."
        docker-compose -f dev.yml stop && \
        docker-compose -f dev.yml rm -f && \
        docker-compose -f dev.yml build
        ;;
    start)
        echo "Starting DEV environment..."
        docker-compose -f dev.yml up -d
        ;;
    stop)
        echo "Stopping DEV environment..."
        docker-compose -f dev.yml stop
        ;;
    restart)
        echo "Restarting DEV environment..."
        docker-compose -f dev.yml stop && \
        docker-compose -f dev.yml up -d
        ;;
    *)
        echo "Invalid program command \"$1\", use:"
        echo "\tbuild"
        echo "\tstart"
        echo "\tstop"
        echo "\trestart"
        ;;
esac