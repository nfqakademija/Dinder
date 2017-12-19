namespace :symfony do

   desc 'Migrations'
   task :migrations do
       on roles :web do
           within release_path do
               execute :php, 'bin/console', 'doctrine:migrations:migrate', '--no-interaction'
           end
       end
   end

   after 'deploy:updated', 'symfony:migrations'
end