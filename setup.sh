#/bin/bash

cd `dirname $0`
export ROOT=`pwd`

rm -rf clone
mkdir clone

# word2vec
cd $ROOT/clone

wget https://storage.googleapis.com/google-code-archive-source/v2/code.google.com/word2vec/source-archive.zip
unzip source-archive.zip

cd word2vec/trunk
make

# moedict
cd $ROOT/clone
git clone git@github.com:g0v/moedict-data.git
git clone git@github.com:g0v/moedict-epub.git
