#!/bin/bash

# Relació de directoris a sincronitzar
# Quan s'afegeixin nous components cal afegir-los a la llista
plugins=(
  "mod/jclic"
  "mod/choicegroup"
  "mod/geogebra"
  "mod/hotpot"
  "question/format/hotpot"
  "mod/journal"
  "mod/questionnaire"
  "mod/qv"
  "mod/rcontent"
  "local/rcommon"
  "blocks/rgrade"
  "blocks/my_books"
  "blocks/completion_progress"
  "blocks/licenses_vicensvives"
  "blocks/courses_vicensvives"
  "local/wsvicensvives"
  "course/format/vv"
  "course/format/simple"
  "lib/editor/atto/plugins/cloze"
  "lib/editor/atto/plugins/fontfamily"
  "lib/editor/atto/plugins/fontsize"
  "local/alexandriaimporter"
  "local/oauth"
  "local/clickedu"
  "report/coursequotas"
  "filter/wiris"
  "lib/editor/atto/plugins/wiris"
  "local/wirisquizzes"
  "question/type/essaywiris"
  "question/type/matchwiris"
  "question/type/multianswerwiris"
  "question/type/multichoicewiris"
  "question/type/shortanswerwiris"
  "question/type/truefalsewiris"
  "question/type/wq"
  "mod/attendance"
  "mod/assign/submission/snap"
  "theme/xtecboost"
  "course/format/grid"
  "mod/subcourse"
  "question/behaviour/adaptivemultipart"
  "question/type/formulas"
  "question/type/drawing"
  "course/format/topcoll"
  "course/format/etask"
  "course/format/trail"
  "mod/msociograma"
  "course/format/remuiformat"
  "mod/offlinequiz"
  "course/format/tiles"
  "filter/syntaxhighlighter"
  "mod/board"
  "mod/exescorm"
  "mod/exeweb"
  "mod/kialo"
)

# Path relatiu de la branca principal
src="../moodle-lab"

# Path del destí on es copiaran els directoris
dest="."

for p in ${plugins[@]}
do
  dir=$(dirname $p)
  if test -d $dest/$p; then
    echo "Updating ${p}"  
    rm -r $dest/$p
  else
    echo "Adding new plugin: ${p}"    
  fi
  mkdir -p $dest/$dir
  cp -a $src/$p $dest/$dir/
done
echo "Done!"
