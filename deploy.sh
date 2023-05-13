#!/bin/bash

git checkout .
git pull
tag=$(git describe --tags --abbrev=0)
echo "Deploying version: ${tag}"
sed -i.bak -r "s/version: '.*?'/version: '${tag}'/g" serverless.yaml
serverless deploy