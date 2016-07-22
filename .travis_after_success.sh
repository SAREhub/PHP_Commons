#!/bin/bash

if [ "$TRAVIS_REPO_SLUG" == "SAREhub/PHP_Commons" ] && [ "$TRAVIS_PULL_REQUEST" == "false" ] && [ "$TRAVIS_PHP_VERSION" == "5.6" ]; then

  echo -e "Publishing PHPDoc...\n"
  cp -R build/docs $HOME/docs-latest

  cd $HOME

  git config --global user.email "travis@travis-ci.org"
  git config --global user.name "travis-ci"
  git clone --quiet --branch=gh-pages https://${GH_TOKEN}@github.com/SAREhub/PHP_Commons > /dev/null

  cd PHP_Commons

  git rm -rf ./docs/$TRAVIS_BRANCH

  mkdir docs
  cd docs
  mkdir $TRAVIS_BRANCH

  cp -Rf $HOME/docs-latest/* ./$TRAVIS_BRANCH/

  git add -f .
  git commit -m "PHPDocumentor (Travis Build : $TRAVIS_BUILD_NUMBER  - Branch : $TRAVIS_BRANCH)"
  git push -fq origin gh-pages > /dev/null
  echo -e "Published PHPDoc to gh-pages.\n"
fi