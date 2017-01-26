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
git clone https://github.com/g0v/moedict-data.git
git clone https://github.com/g0v/moedict-epub.git

# redmansions
cd $ROOT/clone
wget http://www.speedy7.com/cn/stguru/download/Redmansions/AnasoftA/Red_Mansions_Anasoft_A_CHT_Big5_txt.zip
wget http://www.speedy7.com/cn/stguru/download/Redmansions/AnasoftB/Red_Mansions_Anasoft_B_CHT_Big5_txt.zip

unzip Red_Mansions_Anasoft_A_CHT_Big5_txt.zip
unzip Red_Mansions_Anasoft_B_CHT_Big5_txt.zip