# servv_wp_deployment
WP deployment on AKS cluster 
# ** WordPress Deployment on Minikube**

## **📌 Overview**
This repository contains  steps to deploy a **WordPress website** on **Minikube** using **Helm, Docker, and Kubernetes**.

---
## **🔹 Prerequisites**
Ensure you have the following tools installed on your system:

- [Minikube](https://minikube.sigs.k8s.io/docs/start/)
- [Docker](https://docs.docker.com/get-docker/)
- [Kubectl](https://kubernetes.io/docs/tasks/tools/install-kubectl/)
- [Helm](https://helm.sh/docs/intro/install/)
- Git

### **Install Minikube**
Run the following commands to install Minikube:
```bash
curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64
sudo install minikube-linux-amd64 /usr/local/bin/minikube
minikube start --driver=docker
```
Verify installation:
```bash
minikube status
```

---
## **🔹 Step 1: Clone the Repository**
Since the organization provided a customized WordPress website, clone it:

```bash
git clone "Repo-Link"
cd directory-name
```

---
## **🔹 Step 2: Create Helm Charts**
1️⃣ **Create a Helm chart for MySQL inside helm directory:**
```bash
helm create mysql
```
Modify `mysql/values.yaml`, `mysql/templates/deployment.yaml`, `mysql/templates/service.yaml` to set database credentials

2️⃣ **Create a Helm chart for WordPress inside helm directory:**
```bash
helm create wordpress
```
Modify `wordpress/values.yaml`, `wordpress/templates/deployment.yaml`, `wordpress/templates/service.yaml`:

---
## **🔹 Step 3: Build & Push Docker Image**
1️⃣ **Log in to Docker Hub**
```bash
docker login
```

2️⃣ **Set Minikube’s Docker environment**
```bash
eval $(minikube docker-env)
```

3️⃣ **Build the Docker image**
```bash
docker build -t your-dockerhub-user/wordpress:latest .
```

4️⃣ **Push the Docker image to Docker Hub**
```bash
docker push your-dockerhub-user/wordpress:latest
```

---
## **🔹 Step 4: Deploy MySQL Using Helm**
```bash
helm install mysql ./mysql -f mysql/values.yaml
```

---
## **🔹 Step 5: Deploy WordPress Using Helm**
```bash
helm install wordpress ./wordpress -f wordpress/values.yaml
```

---
## **🔹 Step 6: Access the WordPress Site**

### **Option 1: Using Minikube Service Command**
```bash
minikube service wordpress --url
```

### **Option 2: Using Port Forwarding**
If the service is not accessible, manually port forward:
```bash
kubectl port-forward svc/wordpress 8080:80
```
Now, access WordPress at:
```
http://localhost:8080
```

---
## Troubleshooting
### 1. Image Pull Issues
If MySQL or WordPress pods show `ErrImagePull`, ensure that:
- The correct image tag is used.
- Docker is logged in to the correct registry.
- The `values.yaml` files specify the right image.

### 2. Selector & Endpoint Issues
- Ensure that the labels in `mysql/templates/service.yaml` match those in `mysql/templates/deployment.yaml`.
- Run:
  ```sh
  kubectl describe svc mysql
  ```
  - If `Endpoints:` is empty, the selector may not match the pod labels.
  - Update `selectors` in the service file accordingly.

### 3. Database Connection Issues
If WordPress shows *Error establishing a database connection*:
- Ensure `DB_HOST` in `wp-config.php` matches the MySQL service name (`mysql`).
- Check MySQL pod logs:
  ```sh
  kubectl logs mysql-pod-name
  ```
- Verify MySQL credentials:
  ```sh
  kubectl exec -it mysql-pod-name -- mysql -u wordpress -pwordpress-password wordpress_db
  ```
