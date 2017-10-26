#!/bin/bash

if [ "$TRAVIS_REPO_SLUG" == "SAREhub/PHP_Commons" ] && [ $TRAVIS_BRANCH = 'master' ] && [ "$TRAVIS_PULL_REQUEST" == "false" ] && [ "$TRAVIS_PHP_VERSION" == "7.0" ]; then

    mkdir ../gh-pages
    vendor/bin/apigen generate src --destination ../gh-pages --template-theme bootstrap
    cd ../gh-pages

    # Set identity
    git config --global user.email "travis@travis-ci.org"
    git config --global user.name "Travis"

    # Add branch
    git init
    git remote add origin https://${GH_TOKEN}@github.com/${TRAVIS_REPO_SLUG}.git > /dev/null
    git checkout -B gh-pages

    # Push generated files
    git add .
    git commit -m "APIGEN (Travis Build : $TRAVIS_BUILD_NUMBER  - Branch : $TRAVIS_BRANCH)"
    git push origin gh-pages -fq > /dev/null
fi