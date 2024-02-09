## Azure DevOps with Managaed OpenShift

### Prerequirements
- A Public Cloud subscription (Azure Subscription)
- Azure Dev Ops instance
- An OpenShift Cluster
- azure agent (in my case self-hosted on vm)

### Configuration Azure DevOps
First thing to do is create new project or use some already existing and add new azure agent to this project. Important thing is where is you oc cluster 
- if in cloud then you can use some `azure hosted agent` to build and push your application or setup one on `openshift`
- if you are in bare metal cluster (self-hosted) you have to options first one is installationa `self-hosted azure agent` on some vm in your network or just install it on openshift via some helm chart

### Configuration openshift 
First we will create new project for our application 
```shell
oc new-project ado-openshift
```
Create a service account and grant it cluster-admin privileges
```shell
oc create sa azure-sa
oc adm policy add-cluster-role-to-user cluster-admin -z azure-sa
```
Create a secret token for the service account
```shell
cat <<EOF | oc apply -f -
apiVersion: v1
kind: Secret
metadata:
  name: azure-sa-secret
  annotations:
    kubernetes.io/service-account.name: "azure-sa" 
type: kubernetes.io/service-account-token
EOF
```
Retrieve the secret name that we just created that was a token associated with it
```shell
oc get secrets | grep azure-sa-token | awk '{ print $1 }'
```
expected output (note: your output will have a different name):
```shell
azure-sa-token-2qrgw
oc describe secret <secret name>
```
expected output
```shell
Name:         azure-sa-token-2qrgw
Namespace:    ado-openshift
Labels:       <none>
Annotations:  kubernetes.io/created-by: openshift.io/create-dockercfg-secrets
              kubernetes.io/service-account.name: azure-sa
              kubernetes.io/service-account.uid: d361f12e-db7d-412b-9ab8-8ac3a0ba459b

Type:  kubernetes.io/service-account-token

Data
====
token:           eyJhbGciOiJSUzI1NiIsImtpZCI6IlFBcmE2b1N5NnA2OUJZcEh2WUVad1BCSGozck9fa2tpaG83bnctM0hUd00ifQ.eyJpc3MiOiJrdWJlcm5ldGVzL3NlcnZpY2VhY2NvdW50Iiwia3ViZXJuZXRlcy5pby9zZXJ2aWNlYWNjb3VudC9uYW1lc3BhY2UiOiJhZG8tb3BlbnNoaWZ0Iiwia3ViZXJuZXRlcy5pby9zZXJ2aWNlYWNjb3VudC9zZWNyZXQubmFtZSI6ImF6dXJlLXNhLXRva2VuLTJxcmd3Iiwia3ViZXJuZXRlcy5pby9zZXJ2aWNlYWNjb3VudC9zZXJ2aWNlLWFjY291bnQubmFtZSI6ImF6dXJlLXNhIiwia3ViZXJuZXRlcy5pby9zZXJ2aWNlYWNjb3VudC9zZXJ2aWNlLWFjY291bnQudWlkIjoiZDM2MWYxMmUtZGI3ZC00MTJiLTlhYjgtOGFjM2EwYmE0NTliIiwic3ViIjoic3lzdGVtOnNlcnZpY2VhY2NvdW50OmFkby1vcGVuc2hpZnQ6YXp1cmUtc2EifQ.GhLVRAJcG_CHuUxPaz3H_d_E_tGkFK6VaaFv_4UGZiwLLE1Hx-nSIYOA7YsOUvKOkdY2B6fIJrcLUBe5SUjiK0ZePSJZQNry_oZ9xKqhgSRFntxHT5mUR_BXT4cnF5zv0zrT3dvqWcM11mTSs2xfmCx8eACt-uEz2CtLHmxqkvpsiZA2wFQfxekInFFwFhbZSeQk6YBRGFu5f-eawP7qzzDibmo_GmMLHH4uLnpR1CJQFYzI09fEdzSf7IK2UzBgn6dmqpSzHnxLMjgHJVkX66FztJochlGUV8bE4acZk54lu_Xo7OhKxjhiqdeMHFBzq2PeSyvdvSspFME9y6_gXcy1-4QjxLM3t_K9yj7LsJSZKWn8HcmTJy_HoTvpbPtDznz_KEYJH1yX4zdK36D0ocUAb3gBNgfXlsEPAVXYV2o75ZL-AEwpumBv49rRNs_-wZKRO_3eR5zgZWGjZpVoDRb1F_QoFkxy-pnF2sSMQXZOEjwFTapESP182mWZtzzdU8TMOcdK44cr9mYB5IYBmJ2JTRQR2K_iTLfgK-im8O2K5n6OAwWBl4w8mpZDx0eHDp4IBfCBJk2AlopGrQ4TOf-l2bkcEnbJco7ei4D39tRR6xQcPddPEPEDbwIudI9IEzNhyJmHztUnjMV5NaC17hJ05AXWS83nPxFhH_a7pN8
ca.crt:          8717 bytes
namespace:       13 bytes
service-ca.crt:  9930 bytes
```
The last thing is to create a Pull secret in our openshift project, which will give the ability to download an image from our image registry. Unless the repository you are using is public then you can skip this step.
```shell
oc create secret docker-registry regcred --docker-server=<your-registry-server> --docker-username=<your-name> --docker-password=<your-pword> --docker-email=<your-email>
```

### Configure Azure DevOps service connections for the registry and OpenShift
1. Create service connector to you container registry
2. Create service connector to you openshift cluster. The type of this connector should be `kubernetes`
   - Then we need to give our openshift cluster api endpoint for example `https://api.ado-rosa.9s68.p1.openshiftapps.com:6443`
   - Secret - using the name of the secret that we retieved earlier that has token in the name run:
   ```shell
    oc get secret azure-sa-token-2qrgw -o json
   ```
    Copy the entire json results and paste it in the Secret field
   - Service connection name - select openshift

### Change values in pipeline and helm chart
Now when you have all service connectors are deposed we can change some values in those two files
- [pipeline.yml](azure-pipelines.yml)
- [helm chart values.yaml](openshift-deployment/values.yaml)

In pipeline you need to specify two variables on the top with registry path (registry path were you will store your images)<br />
In Helm chart is the same case - paste image registry path for built images `(lines: 8, 36)`<br />
The last change we need to make is in the `values.yaml` file and it's `imagePullSecrets` line 43. Unless you are using a public registry in which case you can bypass this point

### Run you pipeline
Now if you have all done you can push your code to repo and start your pipeline. Note if you are using self-hosted azure agent remember to configure it properly. You need to install all required packages for example helm chart or kubectl in correct version.
[create self-hosted azure agent](https://learn.microsoft.com/en-us/azure/devops/pipelines/agents/agents?view=azure-devops&tabs=yaml%2Cbrowser)