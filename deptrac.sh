#!/bin/bash

# Определим имя лог-файла
LOGFILE="logs/deptrac_analysis.log"
rm -f $LOGFILE

# Функция для удаления .deptrac.cache
delete_cache() {
    if [ -f ".deptrac.cache" ]; then
        echo "Удаление .deptrac.cache..."
        rm -f .deptrac.cache
        echo ".deptrac.cache удалён."
    else
        echo ".deptrac.cache не найден."
    fi
}

# Функция для выполнения анализа Deptrac
run_deptrac_analysis() {
    if [ "$1" == "y" ]; then
        echo -e "\033[92mЗапуск анализа Deptrac с записью в лог..."
        vendor/bin/deptrac analyse --config-file=deptrac.yaml | awk '/--------------------------/ {print_section=1} print_section' &> "$LOGFILE"
        echo -e "\n \033[35mРезультат анализа записан в $LOGFILE.\033[0m"
    else
        echo -e "Запуск анализа Deptrac без записи в лог..."
        vendor/bin/deptrac analyse --config-file=deptrac.yaml
    fi
}

# Предложим пользователю выбор с установкой стандартных значений по умолчанию
echo -e "\033[34mВы хотите удалить .deptrac.cache перед запуском анализа? (y/n, по умолчанию: n)\033[0m"
read -r delete_cache_choice
delete_cache_choice=${delete_cache_choice:-n}  # По умолчанию: "n" (не удалять кэш)

if [ "$delete_cache_choice" == "y" ]; then
    delete_cache
fi

echo -e "\033[34mВы хотите записать результат в лог? (y/n, по умолчанию: y)\033[0m"
read -r log_choice
log_choice=${log_choice:-y}  # По умолчанию: "y" (записывать лог)

# Выполнение анализа Deptrac
run_deptrac_analysis "$log_choice"
