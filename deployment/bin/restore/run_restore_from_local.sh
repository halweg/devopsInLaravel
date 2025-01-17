#!/bin/bash

set -e

SSH_USER=$1
SERVER_IP=$2
BACKUP_FILENAME=$3
MYSQL_USER=$4
MYSQL_PASSWORD=$5

scp -C -o StrictHostKeyChecking=no -i $HOME/.ssh/id_ed25519 $HOME/.ssh/id_ed25519 $SSH_USER@$SERVER_IP:~/.ssh/id_rsa

scp -C -o StrictHostKeyChecking=no -i $HOME/.ssh/id_ed25519 ./restore.sh $SSH_USER@$SERVER_IP:./restore.sh
ssh -tt -o StrictHostKeyChecking=no -i $HOME/.ssh/id_ed25519 $SSH_USER@$SERVER_IP "chmod +x ./restore.sh && ./restore.sh $BACKUP_FILENAME $MYSQL_USER $MYSQL_PASSWORD"
