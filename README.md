# Keep ECDSA Nodes Dashboard

## Live
[https://keep-nodes.com](https://keep-nodes.com)

## Installation in Kubernetes
##### Download repo
```
git clone git@github.com:qwertys318/keep-nodes-dashboard.git
cd keep-nodes-dashboard
```
##### Prepare config
```
cp .env.prod .env
vim .env
```
##### Create namespace
```
kubectl create namespace keep
```
##### Create config secret
```
kubectl create secret generic env --from-file=./.env -n keep
```
##### Apply service, php-fpm deployment, volume and cronjob
```
kubectl apply -f ./deploy/kubernetes.yaml -n keep
```
##### Check service ip
```
$ kubectl get service -n keep
NAME   TYPE        CLUSTER-IP    EXTERNAL-IP   PORT(S)    AGE
web    ClusterIP   10.99.51.16   <none>        9000/TCP   19h
```
##### Configure nginx
```
...
    location / {
        try_files /dev/null /index.php$is_args$args;
    }

    location ~ \.php$ {
        index index.php;

        fastcgi_split_path_info ^(.+\.php)(/.*)$;

        fastcgi_index index.php;
        fastcgi_param SCRIPT_NAME index.php;
        fastcgi_param SCRIPT_FILENAME /var/www/html/public$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT /var/www/html/public;

        fastcgi_pass {SERVICE_IP}:9000;
        include fastcgi_params;
    }

    location ~ \.php$ {
        return 404;
    }
...
```
Enjoy